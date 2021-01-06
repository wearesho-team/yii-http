<?php

namespace Wearesho\Yii\Http;

use Wearesho\Yii\Http\Rest;
use yii\base;
use yii\di;
use yii\web;

/**
 * Class Action
 * @package api\components
 */
class Action extends base\Action
{
    /**
     * @var string[]
     */
    protected $panels;

    /**
     * Action constructor.
     * @param string $id
     * @param Controller $controller
     * @param array $config
     */
    public function __construct($id, Controller $controller, array $panels, array $config = [])
    {
        $this->panels = $panels;
        parent::__construct($id, $controller, $config);
    }

    /**
     * @return web\Response
     *
     * @throws base\InvalidConfigException
     * @throws web\NotFoundHttpException
     *
     * @throws Exceptions\HttpValidationException
     */
    public function run()
    {
        $method = \mb_strtolower(\Yii::$app->request->method);
        if ($method === 'options') {
            return null;
        }

        $className = $this->panels[$method] ?? null;
        if (\is_null($className)) {
            throw new web\NotFoundHttpException();
        }

        /** @var Panel $panel */
        $panel = di\Instance::ensure($className, Panel::class);
        $panel->setAction($this);

        return $panel->getResponse();
    }

    public static function rest(string $modelClass, array $methods = ['get', 'post', 'put', 'patch', 'delete',]): array
    {
        $actions = [
            'get' => [
                'class' => Rest\GetPanel::class,
                'modelClass' => $modelClass,
            ],
            'post' => [
                'class' => Rest\PostForm::class,
                'modelClass' => $modelClass,
            ],
            'put' => [
                'class' => Rest\PutForm::class,
                'modelClass' => $modelClass,
            ],
            'patch' => [
                'class' => Rest\PatchForm::class,
                'modelClass' => $modelClass,
            ],
            'delete' => [
                'class' => Rest\DeleteForm::class,
                'modelClass' => $modelClass,
            ],
        ];

        return \array_intersect_key($actions, \array_flip($methods));
    }
}
