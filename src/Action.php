<?php

namespace Wearesho\Yii\Http;

use yii\base\InvalidConfigException;
use yii\base\Action as BaseAction;

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
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
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

        $panel = \Yii::$container->get($className, [\Yii::$app->request, \Yii::$app->response]);
        if (!$panel instanceof Panel) {
            throw new InvalidConfigException("{$panel} class must extend AbstractPanel");
        }

        return $panel->getResponse();
    }
}