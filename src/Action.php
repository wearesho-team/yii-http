<?php

namespace Wearesho\Yii\Http;

use Wearesho\Yii\Http\Rest\DeleteForm;
use Wearesho\Yii\Http\Rest\GetPanel;
use Wearesho\Yii\Http\Rest\PatchForm;
use Wearesho\Yii\Http\Rest\PostForm;
use Wearesho\Yii\Http\Rest\PutForm;
use yii\base\InvalidConfigException;
use yii\base\Action as BaseAction;

use yii\di\Instance;
use yii\web\NotFoundHttpException;
use yii\web\Response as WebResponse;

/**
 * Class Action
 * @package api\components
 */
class Action extends BaseAction
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
     * @return WebResponse
     *
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     *
     * @throws Exceptions\HttpValidationException
     */
    public function run()
    {
        $method = mb_strtolower(\Yii::$app->request->method);
        if ($method === 'options') {
            return null;
        }

        $className = $this->panels[$method] ?? null;
        if (is_null($className)) {
            throw new NotFoundHttpException();
        }

        /** @var Panel $panel */
        $panel = Instance::ensure($className, Panel::class);

        return $panel->getResponse();
    }

    public static function rest(string $modelClass, array $methods = ['get', 'post', 'put', 'patch', 'delete',]): array
    {
        $actions = [
            'get' => [
                'class' => GetPanel::class,
                'modelClass' => $modelClass,
            ],
            'post' => [
                'class' => PostForm::class,
                'modelClass' => $modelClass,
            ],
            'put' => [
                'class' => PutForm::class,
                'modelClass' => $modelClass,
            ],
            'patch' => [
                'class' => PatchForm::class,
                'modelClass' => $modelClass,
            ],
            'delete' => [
                'class' => DeleteForm::class,
                'modelClass' => $modelClass,
            ],
        ];

        return array_filter($actions, function ($method) use ($methods) {
            return in_array($method, $methods);
        }, ARRAY_FILTER_USE_KEY);
    }
}
