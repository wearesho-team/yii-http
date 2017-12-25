<?php

namespace Wearesho\Yii\Http\Tests\Mocks;

use Wearesho\Yii\Http\View;

/**
 * Class ViewMock
 * @package Wearesho\Yii\Http\Tests\Mocks
 */
class ViewMock extends View
{
    /** @var array */
    protected $output;

    public function __construct(array $output = [])
    {
        $this->output = $output;
    }

    protected function renderInstantiated(): array
    {
        return (array)$this->output;
    }
}
