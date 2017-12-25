<?php


namespace Wearesho\Yii\Http\Tests\Common;


use Wearesho\Yii\Http\Tests\AbstractTestCase;
use Wearesho\Yii\Http\Tests\Mocks\ViewMock;

class ViewTest extends AbstractTestCase
{
    public function testRender()
    {
        $expectedOutput = [
            mt_rand() => mt_rand(),
        ];
        $output = ViewMock::render($expectedOutput);
        $this->assertEquals($expectedOutput, $output);
    }
}