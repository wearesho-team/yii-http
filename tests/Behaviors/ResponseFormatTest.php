<?php

namespace Wearesho\Yii\Http\Tests\Behaviors;

use Wearesho\Yii\Http;

use yii\base;


/**
 * Class ResponseFormatTest
 * @package Wearesho\Yii\Http\Tests\Behaviors
 */
class ResponseFormatTest extends Http\Tests\AbstractTestCase
{
    public function testSet(): void
    {
        $response = new Http\Response();
        $behavior = new Http\Behaviors\ResponseFormat($response, [
            'format' => Http\Response::FORMAT_RAW,
        ]);
        $behavior->setFormat();

        $this->assertEquals(Http\Response::FORMAT_RAW, $response->format);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage Invalid response format
     */
    public function testInvalidFormat(): void
    {
        $behavior = new Http\Behaviors\ResponseFormat(new Http\Response());
        $behavior->setFormat();
    }

    public function testTrigger(): void
    {
        $fakeController = new class('id_controller', new base\Module('id_module')) extends Http\Controller
        {
            public function __construct(string $id, base\Module $module, array $config = [])
            {
                parent::__construct($id, $module, $config);
            }

            public function behaviors(): array
            {
                return [
                    'get' => [
                        'class' => Http\Behaviors\ResponseFormat::class,
                        'format' => Http\Response::FORMAT_JSON,
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
        $fakeController->trigger(Http\Controller::EVENT_BEFORE_ACTION);

        $this->assertArraySubset(
            ['key' => 'value',],
            $action->runWithParams([])
        );
    }
}
