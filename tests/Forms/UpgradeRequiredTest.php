<?php declare(strict_types=1);

namespace Wearesho\Yii\Http\Tests\Forms;

use Wearesho\Yii\Http\Tests\AbstractTestCase;
use Wearesho\Yii\Http\Panels;
use yii\web;

class UpgradeRequiredTest extends AbstractTestCase
{
    protected Panels\UpgradeRequired  $panel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->panel = \Yii::$container->get(Panels\UpgradeRequired::class);
    }

    public function testThrowingExceptions(): void
    {
        $this->expectException(web\HttpException::class);
        $this->expectExceptionMessage('This method was deprecated. Contact developers for details.');
        $this->panel->getResponse();
    }

    public function testCustomExceptionMessage(): void
    {
        $exceptionMessage = 'Custom Text.';
        $this->panel->message = $exceptionMessage;
        $this->expectException(web\HttpException::class);
        $this->expectExceptionMessage($exceptionMessage);
        $this->panel->getResponse();
    }
}
