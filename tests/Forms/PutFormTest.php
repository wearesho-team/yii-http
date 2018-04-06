<?php

namespace Wearesho\Yii\Http\Tests\Forms;

use Wearesho\Yii\Http\Rest\PutForm;
use Wearesho\Yii\Http\Tests\AbstractTestCase;
use Wearesho\Yii\Http\Tests\Mocks\RecordMock;

class PutFormTest extends AbstractTestCase
{
    public function testSettingScenario()
    {
        /** @var PutForm $form */
        $form = \Yii::$container->get(PutForm::class, [], [
            'modelClass' => RecordMock::class,
            'scenario' => RecordMock::SCENARIO_TEST,
        ]);

        $scenarios = $form->scenarios();
        $this->assertArrayHasKey(RecordMock::SCENARIO_TEST, $scenarios);

        $this->assertTrue(true);
    }
}
