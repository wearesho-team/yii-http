<?php

namespace Wearesho\Yii\Http\Tests\Mocks;

use Wearesho\Yii\Http;

/**
 * Class PanelMock
 * @package Wearesho\Yii\Http\Tests\Mocks
 */
class PanelMock extends Http\Panel
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    public function behaviors(): array
    {
        return [
            'get' => [
                'class' => Http\Behaviors\GetParamsBehavior::class,
                'attributes' => [
                    'id',
                    'name'
                ],
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [
                ['id', ],
                'integer',
            ],
            [
                ['name', ],
                'string',
            ],
            [
                ['id', 'name', ],
                'required',
            ]
        ];
    }

    protected function generateResponse(): array
    {
        return [
            'paneMock' => 'testResult',
        ];
    }
}
