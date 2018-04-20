<?php

namespace Wearesho\Yii\Http\Tests;

use Wearesho\Yii\Http\Controller;
use Wearesho\Yii\Http\Rest;
use yii\base\Module;

class ControllerTest extends AbstractTestCase
{

    protected $controller;

    protected function setUp()
    {
        parent::setUp();

        $this->controller= new Controller("id_controller", new Module("id_module"));
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
                'optional' => []
            ],
            'corsFilter' => [
                'class' => 'yii\filters\Cors'
            ],
            'verbs' => [
                'class' => 'yii\filters\VerbFilter',
                'actions' => []
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
            };

        $localController->actions = [
            'get' => [

            ],
            'post' => [
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

            ],
            'post' => [
                Rest\PostForm::class,
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
    /*
          public function testCreateAction()
          {

          }
      */
}
