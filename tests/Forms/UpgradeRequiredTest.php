<?php

namespace Wearesho\Yii\Http\Tests\Forms;

use Wearesho\Yii\Http\Tests\AbstractTestCase;
use Wearesho\Yii\Http\Panels;

/**
 * Class UpgradeRequiredTest
 * @package Wearesho\Yii\Http\Tests\Forms
 */
class UpgradeRequiredTest extends AbstractTestCase
{
    /** @var Panels\UpgradeRequired */
    protected $panel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->panel = \Yii::$container->get(Panels\UpgradeRequired::class);
    }

    /**
     * @expectedException \yii\web\HttpException
     * @expectedExceptionMessage This method was deprecated. Contact developers for details.
     */
    public function testThrowingExceptions()
    {
        $this->panel->getResponse();
    }

    /**
     * @expectedException \yii\web\HttpException
     * @expectedExceptionMessage Custom Text.
     */
    public function testCustomExceptionMessage()
    {
        $this->panel->message = 'Custom Text.';
        $this->panel->getResponse();
    }
}
