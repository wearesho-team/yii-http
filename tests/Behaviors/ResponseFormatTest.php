<?php

namespace Wearesho\Yii\Http\Tests\Behaviors;

use Wearesho\Yii\Http\Behaviors\ResponseFormat;
use Wearesho\Yii\Http\Response;
use Wearesho\Yii\Http\Tests\AbstractTestCase;

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
}
