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

        /** @noinspection PhpUnhandledExceptionInspection */
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
        $methods = [
            'get' => [
                'class' => 'Wearesho\Yii\Http\Rest\GetPanel',
                'modelClass' => 'yii\base\ModelEvent',
            ],
            'post' => [
                'class' => 'Wearesho\Yii\Http\Rest\PostForm',
                'modelClass' => 'yii\base\ModelEvent',
            ],
            'put' => [
                'class' => 'Wearesho\Yii\Http\Rest\PutForm',
                'modelClass' => 'yii\base\ModelEvent',
            ],
            'patch' => [
                'class' => 'Wearesho\Yii\Http\Rest\PatchForm',
                'modelClass' => 'yii\base\ModelEvent',
            ],
            'delete' => [
                'class' => 'Wearesho\Yii\Http\Rest\DeleteForm',
                'modelClass' => 'yii\base\ModelEvent',
            ],
        ];

        // random unit
        for ($i = 0; $i < mt_rand(1, 10); $i++) {
            $randMethodKeys = [];

            foreach ($methods as $method => $parameters) {
                !rand(0, 1) ?: $randMethodKeys[$method] = $method;
            }

            $expectedMethods = array_filter($methods, function ($method) use ($randMethodKeys) {
                return in_array($method, $randMethodKeys);
            }, ARRAY_FILTER_USE_KEY);

            $this->assertEquals(
                $expectedMethods,
                $this->action->rest(ModelEvent::class, $randMethodKeys)
            );
        }

        // simple unit
        $this->assertEquals(
            [
                'get' => [
                    'class' => 'Wearesho\Yii\Http\Rest\GetPanel',
                    'modelClass' => 'yii\base\ModelEvent',
                ],
                'post' => [
                    'class' => 'Wearesho\Yii\Http\Rest\PostForm',
                    'modelClass' => 'yii\base\ModelEvent',
                ],
            ],
            $this->action->rest(
                ModelEvent::class,
                [
                    'get',
                    'post',
                ]
            )
        );
        $this->assertEquals(
            [
                'get' => [
                    'class' => 'Wearesho\Yii\Http\Rest\GetPanel',
                    'modelClass' => 'yii\base\ModelEvent',
                ],
                'post' => [
                    'class' => 'Wearesho\Yii\Http\Rest\PostForm',
                    'modelClass' => 'yii\base\ModelEvent',
                ],
                'put' => [
                    'class' => 'Wearesho\Yii\Http\Rest\PutForm',
                    'modelClass' => 'yii\base\ModelEvent',
                ],
                'patch' => [
                    'class' => 'Wearesho\Yii\Http\Rest\PatchForm',
                    'modelClass' => 'yii\base\ModelEvent',
                ],
                'delete' => [
                    'class' => 'Wearesho\Yii\Http\Rest\DeleteForm',
                    'modelClass' => 'yii\base\ModelEvent',
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
        $this->assertNull(
            $this->action->run()
        );
    }

    /**
     * @expectedException yii\base\InvalidConfigException
     * @expectedExceptionMessage Connection::dsn cannot be empty.
     */
    public function testRunPost(): void
    {
        $_SERVER['REQUEST_METHOD'] = "POST";

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->action->run();
    }
}
