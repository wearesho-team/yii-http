<?php

namespace Wearesho\Yii\Http\Rest;

/**
 * Trait ScenarioTrait
 * @package Wearesho\Yii\Http\Rest
 *
 * @property string $scenario
 */
trait ScenarioTrait
{
    public function scenarios(): array
    {
        return [$this->scenario => []];
    }
}
