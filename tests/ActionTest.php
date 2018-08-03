<?php

namespace Wearesho\Yii\Http\Tests;

use Horat1us\Yii\Helpers\ArrayHelper;

use PHPUnit\Framework\TestResult;

use Wearesho\Yii\Http;

use yii\base;

/**
 * Class ActionTest
 * @package Wearesho\Yii\Http\Tests
 *
 * @internal
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

    public function testFull(): void
    {
        $rest = $this->action->rest(base\Model::class);
        $this->assertArraySubset(
            [
                'get' => [
                    'class' => Http\Rest\GetPanel::class,
                    'modelClass' => base\Model::class,
                ],
                'post' => [
                    'class' => Http\Rest\PostForm::class,
                    'modelClass' => base\Model::class,
                ],
                'put' => [
                    'class' => Http\Rest\PutForm::class,
                    'modelClass' => base\Model::class,
                ],
                'patch' => [
                    'class' => Http\Rest\PatchForm::class,
                    'modelClass' => base\Model::class,
                ],
                'delete' => [
                    'class' => Http\Rest\DeleteForm::class,
                    'modelClass' => base\Model::class,
                ],
            ],
            $rest
        );
    }

    public function testCertainMethods(): void
    {
        $rest = $this->action->rest(base\Model::class, ['post', 'patch', 'delete',]);
        $this->assertArraySubset(
            [
                'post' => [
                    'class' => Http\Rest\PostForm::class,
                    'modelClass' => base\Model::class,
                ],
                'patch' => [
                    'class' => Http\Rest\PatchForm::class,
                    'modelClass' => base\Model::class,
                ],
                'delete' => [
                    'class' => Http\Rest\DeleteForm::class,
                    'modelClass' => base\Model::class,
                ],
            ],
            $rest
        );
    }

    /**
     * @expectedException \yii\web\NotFoundHttpException
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
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage Connection::dsn cannot be empty.
     */
    public function testRunPost(): void
    {
        $_SERVER['REQUEST_METHOD'] = "POST";

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->action->run();
    }
}
