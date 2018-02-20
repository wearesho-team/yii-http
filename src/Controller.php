<?php

namespace Wearesho\Yii\Http;

use yii\base\InlineAction;

use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\filters\VerbFilter;

use yii\rest\OptionsAction;
use yii\web\Controller as WebController;

/**
 * Class Controller
 * @package Wearesho\Yii\Http
 */
class Controller extends WebController
{
    /**
     * Declares external actions for the controller.
     *
     * For example,
     *
     * ```php
     * return [
     *     'action2' => [
     *         'get' => \App\Http\Panel::class,
     *         'post' => [
     *              'class' => \App\Http\Form::class
     *              'property' => 'value',
     *         ],
     *     ],
     * ];
     * ```
     *
     * [[\Yii::createObject()]] will be used later to create the requested action
     * using the configuration provided here.
     *
     * @var array
     */
    public $actions = [];

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'authenticator' => [
                'class' => HttpBearerAuth::class,
                'optional' => array_keys($this->actions()),
            ],
            'corsFilter' => [
                'class' => Cors::class,
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => array_map(function ($panels) {
                    return array_merge(['OPTIONS'], array_keys($panels));
                }, $this->actions()),
            ],
        ];
    }

    /**
     * @see $actions
     * @return array
     */
    public function actions()
    {
        return $this->actions;
    }

    /**
     * @param string $id
     * @return Action|null|InlineAction
     */
    public function createAction($id)
    {
        if ($id === '') {
            $id = $this->defaultAction;
        }

        $actionMap = $this->actions();
        if (isset($actionMap[$id])) {
            return new Action($id, $this, $actionMap[$id]);
        } elseif (preg_match('/^[a-z0-9\\-_]+$/', $id) && strpos($id, '--') === false && trim($id, '-') === $id) {
            $methodName = 'action' . str_replace(' ', '', ucwords(implode(' ', explode('-', $id))));
            if (method_exists($this, $methodName)) {
                $method = new \ReflectionMethod($this, $methodName);
                if ($method->isPublic() && $method->getName() === $methodName) {
                    return new InlineAction($id, $this, $methodName);
                }
            }
        }

        return null;
    }
}
