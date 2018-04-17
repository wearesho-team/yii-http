<?php

namespace Wearesho\Yii\Http\Tests;

use Wearesho\Yii\Http\Action;
use Wearesho\Yii\Http\Controller;
use yii\base\Module;

class ControllerTest extends AbstractTestCase
{

    protected $controller;

    protected function setUp()
    {
        parent::setUp();

        $this->controller= new Controller("id_controller", new Module("id_module"));
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

    public function testBeforeAction()
    {
        $this->controller->actions[]=[
                'get' => "SomeGet",
             'post' => "SomePost",
        ];

        $action = $this->controller->createAction("id_action");

        $beforeAction = $this->controller->beforeAction($action);

        $this->assertEquals(true, $beforeAction);
    }
    /*
          public function testCreateAction()
          {

          }
      */
}
