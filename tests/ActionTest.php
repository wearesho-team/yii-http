<?php

namespace Wearesho\Yii\Http\Tests;

use Horat1us\Yii\Helpers\ArrayHelper;
use PHPUnit\Framework\TestResult;
use Wearesho\Yii\Http\Action;
use Wearesho\Yii\Http\Controller;
use Wearesho\Yii\Http\Rest\PostForm;
use yii;
use yii\base\ModelEvent;
use yii\base\Module;

/**
 * Class ActionTest
 * @package Wearesho\Yii\Http\Tests
 */
class ActionTest extends AbstractTestCase
{
    /** @var Action */
    protected $action;

    public function setUp(): void
    {
        parent::setUp();
        $this->action = \Yii::$container->get(
            Action::class,
            [
                "id_action",
                \Yii::$container->get(Controller::class, [
                    "id_controller",
                    \Yii::$container->get(Module::class, ["id_module"])
                ]),
                [
                    "post" => [
                        'class' => PostForm::class
                    ]
                ]
            ]
        );
    }

    protected function appConfig(): array
    {
        return ArrayHelper::merge(parent::appConfig(), [
            'components' => [
                'request' => [
                    'class' => \Wearesho\Yii\Http\Request::class,
                ],
            ]
        ]);
    }

    public function testRest(): void
    {
        $this->assertEquals(
            [
                'get' => [
                    'class' => 'Wearesho\Yii\Http\Rest\GetPanel',
                    'modelClass' => 'yii\base\ModelEvent'
                ],
                'post' => [
                    'class' => 'Wearesho\Yii\Http\Rest\PostForm',
                    'modelClass' => 'yii\base\ModelEvent'
                ],
                'put' => [
                    'class' => 'Wearesho\Yii\Http\Rest\PutForm',
                    'modelClass' => 'yii\base\ModelEvent'
                ],
                'patch' => [
                    'class' => 'Wearesho\Yii\Http\Rest\PatchForm',
                    'modelClass' => 'yii\base\ModelEvent'
                ],
                'delete' => [
                    'class' => 'Wearesho\Yii\Http\Rest\DeleteForm',
                    'modelClass' => 'yii\base\ModelEvent'
                ],
            ],
            $this->action->rest(ModelEvent::class)
        );
    }

    /**
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testRunException(): void
    {
        $runResult = $this->action->run();

        $this->assertEquals(
            new TestResult(),
            $runResult
        );
    }

    public function testRunOptions(): void
    {
        $_SERVER['REQUEST_METHOD'] = "OPTIONS";
        $this->assertEquals(
            null,
            $this->action->run()
        );
    }

    /**
     * @expectedException yii\base\InvalidConfigException
     */
    public function testRunPost(): void
    {
        $_SERVER['REQUEST_METHOD'] = "POST";
        $this->action->run();
    }
}
