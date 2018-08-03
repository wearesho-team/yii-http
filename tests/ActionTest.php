<?php

namespace Wearesho\Yii\Http\Tests;

use Horat1us\Yii\Helpers\ArrayHelper;

use PHPUnit\Framework\TestResult;

use Wearesho\Yii\Http;

use yii;
use yii\base;

/**
 * Class ActionTest
 * @package Wearesho\Yii\Http\Tests
 */
class ActionTest extends AbstractTestCase
{
    /** @var Http\Action */
    protected $action;

    public function setUp(): void
    {
        parent::setUp();

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->action = \Yii::$container->get(
            Http\Action::class,
            [
                "id_action",
                \Yii::$container->get(Http\Controller::class, [
                    "id_controller",
                    \Yii::$container->get(base\Module::class, ["id_module"])
                ]),
                [
                    "post" => [
                        'class' => Http\Rest\PostForm::class
                    ]
                ]
            ]
        );
    }

    protected static function appConfig(): array
    {
        return ArrayHelper::merge(parent::appConfig(), [
            'components' => [
                'request' => [
                    'class' => Http\Request::class,
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
            $this->action->rest(base\ModelEvent::class)
        );
    }

    /**
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testRunException(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $runResult = $this->action->run();

        $this->assertEquals(
            new TestResult(),
            $runResult
        );
    }

    public function testRunOptions(): void
    {
        $_SERVER['REQUEST_METHOD'] = "OPTIONS";
        /** @noinspection PhpUnhandledExceptionInspection */
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
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->action->run();
    }
}
