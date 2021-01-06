<?php

namespace Wearesho\Yii\Http;

use yii\base;
use yii\filters;
use yii\helpers\ArrayHelper;
use yii\web;

/**
 * Class Controller
 * @package Wearesho\Yii\Http
 */
class Controller extends web\Controller
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
     * @var array
     * @see behaviors()
     */
    public $behaviors = [];

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge([
            'authenticator' => [
                'class' => Behaviors\HttpBearerAuth::class,
                'optional' => \array_keys($this->actions()),
            ],
            'corsFilter' => [
                'class' => filters\Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Max-Age' => 86400,
                ],
            ],
            'verbs' => [
                'class' => filters\VerbFilter::class,
                'actions' => \array_map(function ($panels) {
                    return \array_merge(['OPTIONS'], \array_keys($panels));
                }, $this->actions()),
            ],
        ], $this->behaviors);
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
     * @return base\Action|null|base\InlineAction
     * @throws \ReflectionException
     */
    public function createAction($id)
    {
        if ($id === '') {
            $id = $this->defaultAction;
        }

        $actionMap = $this->actions();
        if (isset($actionMap[$id])) {
            return new Action($id, $this, $actionMap[$id]);
        } elseif (\preg_match('/^[a-z0-9\\-_]+$/', $id) && \strpos($id, '--') === false && \trim($id, '-') === $id) {
            $methodName = 'action' . \str_replace(' ', '', \ucwords(\implode(' ', \explode('-', $id))));
            if (\method_exists($this, $methodName)) {
                $method = new \ReflectionMethod($this, $methodName);
                if ($method->isPublic() && $method->getName() === $methodName) {
                    return new base\InlineAction($id, $this, $methodName);
                }
            }
        }

        return null;
    }

    /**
     * @param $action
     * @return bool
     * @throws base\ExitException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (\Yii::$app->request->method === 'OPTIONS') {
                \Yii::$app->response->send();
                throw new base\ExitException();
            }

            return true;
        }

        return false;
    }
}
