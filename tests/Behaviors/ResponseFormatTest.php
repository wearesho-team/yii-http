<?php

namespace Wearesho\Yii\Http\Tests\Behaviors;

use Wearesho\Yii\Http\Behaviors\ResponseFormat;
use Wearesho\Yii\Http\Controller;
use Wearesho\Yii\Http\Panel;
use Wearesho\Yii\Http\Response;
use Wearesho\Yii\Http\Rest\GetPanel;
use Wearesho\Yii\Http\Tests\AbstractTestCase;
use yii\base\Model;
use yii\base\Module;

/**
 * Class ResponseFormatTest
 * @package Wearesho\Yii\Http\Tests\Behaviors
 */
class ResponseFormatTest extends AbstractTestCase
{
    public function testSet(): void
    {
        $response = new Response();
        $behavior = new ResponseFormat($response, [
            'format' => Response::FORMAT_RAW,
        ]);
        $behavior->setFormat();

        $this->assertEquals(Response::FORMAT_RAW, $response->format);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage Invalid response format
     */
    public function testInvalidFormat(): void
    {
        $behavior = new ResponseFormat(new Response());
        $behavior->setFormat();
    }

    public function testTrigger(): void
    {
        $fakeController = new class('id_controller', new Module('id_module')) extends Controller
        {
            public function __construct(string $id, Module $module, array $config = [])
            {
                parent::__construct($id, $module, $config);
            }

            public function behaviors(): array
            {
                return [
                    'get' => [
                        'class' => ResponseFormat::class,
                        'format' => Response::FORMAT_JSON,
                    ],
                ];
            }

            public function actionTest(): array
            {
                return [
                    'key' => 'value'
                ];
            }
        };

        $fakeController->enableCsrfValidation = false;
        $action = $fakeController->createAction('test');
        $fakeController->trigger(Controller::EVENT_BEFORE_ACTION);

        $this->assertArraySubset(
            ['key' => 'value',],
            $action->runWithParams([])
        );
    }
}
