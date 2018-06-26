<?php

namespace Wearesho\Yii\Http\Tests;

use PHPUnit\Framework\TestCase;
use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use Wearesho\Yii\Http\Response;
use yii\base\Model;
use yii\db\Exception;

class ResponseTest extends TestCase
{

    public function testSetStatusCodeByException()
    {
        $response = new Response([
            "charset" => "Response Charset",
            "content" => "Simple Fantastic Content"
        ]);

        $resultSimple = $response->setStatusCodeByException(new Exception("Something wrong"));

        $this->assertNotNull($resultSimple);

        $resultHttp = $response->setStatusCodeByException(new HttpValidationException(new Model()));

        $this->assertNotNull($resultHttp);
        $this->assertInstanceOf(Response::class, $resultHttp);
        $this->assertEquals("Simple Fantastic Content", $resultHttp->content);
        $this->assertEquals("Response Charset", $resultHttp->charset);
    }
}
