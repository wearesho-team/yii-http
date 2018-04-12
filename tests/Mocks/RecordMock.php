<?php

namespace Wearesho\Yii\Http\Tests\Mocks;

use yii\db\ActiveRecord;

/**
 * Class RecordMock
 * @package Wearesho\Yii\Http\Tests\Mocks
 */
class RecordMock extends ActiveRecord
{
    const SCENARIO_TEST = 'testScenario';

    public function scenarios(): array
    {
        return [static::SCENARIO_TEST => []];
    }
}
