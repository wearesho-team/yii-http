<?php declare(strict_types=1);

namespace Wearesho\Yii\Http\Tests;

use Horat1us\Yii\Helpers\ArrayHelper;

use PHPUnit\Framework\TestResult;

use Wearesho\Yii\Http;

use yii\base;
use yii\web;

/**
 * Class ActionTest
 * @package Wearesho\Yii\Http\Tests
 *
 * @internal
 */
class ActionTest extends AbstractTestCase
{
    /** @var Http\Action */
    protected $httpAction;

    public function setUp(): void
    {
        parent::setUp();

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->httpAction = \Yii::$container->get(
            Http\Action::class,
            [
                "id_action",
                \Yii::$container->get(Http\Controller::class, [
                    "id_controller",
                    \Yii::$container->get(base\Module::class, ["id_module"])
                ]),
                [
                    "post" => [
                        'class' => Http\Tests\Mocks\PanelMock::class
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
        $rest = $this->httpAction->rest(base\Model::class);
        $this->assertEquals(
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
        $rest = $this->httpAction->rest(base\Model::class, ['post', 'patch', 'delete',]);
        $this->assertEquals(
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

    public function testPassingActionToPanel(): void
    {
        $_SERVER['REQUEST_METHOD'] = "POST";
        \Yii::$container->setSingleton(Http\Tests\Mocks\PanelMock::class);
        /** @var Http\Tests\Mocks\PanelMock $panel */
        $panel = \Yii::$container->get(Http\Tests\Mocks\PanelMock::class);

        $_GET['id'] = 1;
        $_GET['name'] = 'Name';

        $this->httpAction->run();
        $this->assertEquals(
            $this->httpAction,
            $panel->action
        );
    }

    public function testRunOptions(): void
    {
        $_SERVER['REQUEST_METHOD'] = "OPTIONS";
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(
            null,
            $this->httpAction->run()
        );
    }

    public function testRunGet(): void
    {
        $_SERVER['REQUEST_METHOD'] = "GET";

        $this->expectException(web\NotFoundHttpException::class);
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->httpAction->run();
    }
}
