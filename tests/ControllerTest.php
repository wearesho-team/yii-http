<?php

namespace Wearesho\Yii\Http\Tests;

use Wearesho\Yii\Http\Controller;
use Wearesho\Yii\Http\Rest;
use yii\base\Event;
use yii\base\Module;

class ControllerTest extends AbstractTestCase
{

    protected $controller;

    protected function setUp()
    {
        parent::setUp();

        $this->controller= new Controller("id_controller", new Module("id_module"));

        $this->controller->actions = [
            'get' => [

            ],
            'actionPost' => [
                Rest\PostForm::class,
            ],
        ];
    }

    protected function appConfig(): array
    {
        return [
            'id' => 'yii-register-confirmation-test',
            'basePath' => dirname(__DIR__),
            'components' => [
                'request' => [
                    'class' => \yii\web\Request::class,
                ],
            ],
        ];
    }

    public function testBehaviors()
    {
        $this->assertEquals([
            'authenticator' => [
                'class' => 'yii\filters\auth\HttpBearerAuth',
                'optional' => [
                    0 => 'get',
                    1 => 'actionPost'
                ]
            ],
            'corsFilter' => [
                'class' => 'yii\filters\Cors'
            ],
            'verbs' => [
                'class' => 'yii\filters\VerbFilter',
                'actions' => [
                    'get' => [
                        0 => 'OPTIONS'
                    ],
                    'actionPost' => [
                        0 => 'OPTIONS',
                        1 => 0
                    ]
                ]
            ],
        ], $this->controller->behaviors());
    }

    public function testWithActionMap()
    {
        $localController =
            new class("id_controller", new Module("id_module")) extends Controller
            {
                function behaviors(): array
                {
                    return [];
                }

                function actionPost()
                {
                }
            };

        $localController->actions = [
            'get' => [

            ],
            'actionPost' => [
                Rest\PostForm::class,
            ],
        ];

        $_REQUEST = new \yii\web\Request();

        $action = $localController->createAction("post");

        $beforeAction = $localController->beforeAction($action);

        $this->assertEquals(true, $beforeAction);
    }

    public function testWithOptionsMethod()
    {
        $localController =
            new class("id_controller", new Module("id_module")) extends Controller
            {
                function behaviors(): array
                {
                    return [];
                }
            };

        $localController->actions = [
            'get' => [
                Rest\GetPanel::class,
            ],
            'post' => [
                Rest\PostForm::class,
            ],
            'delete' => [
                Rest\DeleteForm::class,
            ],
        ];

        $_REQUEST = new \yii\web\Request();

        $action = $localController->createAction("post");

        $beforeAction = $localController->beforeAction($action);

        $this->assertEquals(true, $beforeAction);
    }


    public function testWithoutActionMap()
    {
        $localController =
            new class("id_controller", new Module("id_module")) extends Controller
            {
                function behaviors(): array
                {
                    return [];
                }
            };

        $_REQUEST = new \yii\web\Request();

        $action = $localController->createAction("post");

        $beforeAction = $localController->beforeAction($action);

        $this->assertEquals(true, $beforeAction);
    }

    public function testWithEmptyId()
    {
        $localController =
            new class("id_controller", new Module("id_module")) extends Controller
            {
                function behaviors(): array
                {
                    return [];
                }
            };

        $_REQUEST = new \yii\web\Request();

        $action = $localController->createAction("");

        $beforeAction = $localController->beforeAction($action);

        $this->assertEquals(true, $beforeAction);
    }

    public function testBeforeActionFalse()
    {
        $localController =
            new class("id_controller", new Module("id_module")) extends Controller
            {
                function behaviors(): array
                {
                    return [];
                }

            };

        $localController->on(
            \yii\base\Controller::EVENT_BEFORE_ACTION,
            function (Event $event) {
                $event->isValid = false;
            }
        );

        $action = $localController->createAction("weq");

        $beforeAction = $localController->beforeAction($action);

        $this->assertEquals(false, $beforeAction);
    }

    /**
     * @throws \yii\base\ExitException
     * @throws \yii\web\BadRequestHttpException
     * @expectedException \yii\base\ExitException
     */
    public function testBeforeActionTrue()
    {
        $localController =
            new class("id_controller", new Module("id_module")) extends Controller
            {
                function behaviors(): array
                {
                    return [];
                }
            };

        $_REQUEST = new \yii\web\Request();
        $_SERVER["REQUEST_METHOD"] = "OPTIONS";

        $action = $localController->createAction("weq");

        $beforeAction = $localController->beforeAction($action);

        $this->assertEquals(true, $beforeAction);
    }
}
