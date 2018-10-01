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
        \Yii::$app->response->format = Http\Response::FORMAT_HTML;

        $fakeController = new class('id', new base\Module('id')) extends Http\Controller
        {
            public $actions = [
                'test' => ['get' => 'test',],
            ];

            public function behaviors(): array
            {
                return [
                    'responseFormat' => [
                        'class' => Http\Behaviors\ResponseFormat::class,
                        'format' => Http\Response::FORMAT_JSON,
                    ],
                ];
            }
        };

        $fakeController->enableCsrfValidation = false;
        $fakeController->trigger(
            Http\Controller::EVENT_BEFORE_ACTION,
            new base\ActionEvent($fakeController->createAction('test'))
        );

        $this->assertEquals(Http\Response::FORMAT_JSON, \Yii::$app->response->format);
    }
}
