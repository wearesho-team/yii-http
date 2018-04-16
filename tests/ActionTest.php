<?php

namespace Wearesho\Yii\Http\Tests;

use Horat1us\Yii\Helpers\ArrayHelper;
use PHPUnit\Framework\TestResult;
use Wearesho\Yii\Http\Action;
use Wearesho\Yii\Http\Controller;
use yii\base\ModelEvent;
use yii\base\Module;
use yii\web\Request;
use yii\web\NotFoundHttpException;

class ActionTest extends AbstractTestCase
{
    protected $action;

    public function setUp()
    {
        parent::setUp();
        $this->action = \Yii::$container->get(Action::class, [
            "id_action",
            new Controller("id_controller", new Module("id_string")),
            []
        ]);
    }

    protected function appConfig(): array
    {
        return ArrayHelper::merge(parent::appConfig(), [
            'components' => [
                'request' => \Wearesho\Yii\Http\Request::class,
            ]
        ]);
    }

    public function testRest()
    {
        $this->assertEquals(
            [
                "get" => [
                    'class' => 'Wearesho\Yii\Http\Rest\GetPanel',
                    'modelClass' => 'yii\base\ModelEvent'
                ],
                "post" => [
                    'class' => 'Wearesho\Yii\Http\Rest\PostForm',
                    'modelClass' => 'yii\base\ModelEvent'
                ],
                "put" => [
                    'class' => 'Wearesho\Yii\Http\Rest\PutForm',
                    'modelClass' => 'yii\base\ModelEvent'
                ],
                "patch" => [
                    'class' => 'Wearesho\Yii\Http\PatchForm',
                    'modelClass' => 'yii\base\ModelEvent'
                ],
                "delete" => [
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
    public function testRunException()
    {
        $runResult = $this->action->run();

        $this->assertEquals(
            new TestResult(),
            $runResult
        );
    }

    public function testRun()
    {
        $runResult = $this->action->run();

        $this->assertEquals(
            new TestResult(),
            $runResult
        );
    }
}
