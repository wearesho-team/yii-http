<?php

namespace Wearesho\Yii\Http\Tests\Mocks;

use Wearesho\Yii\Http\AccessRule;
use Wearesho\Yii\Http\Behaviors\AccessControl;
use Wearesho\Yii\Http\Form;

class AccessRuleFormMock extends Form
{
    public const TEST_PERMISSION = 'testPermission';

    public function behaviors()
    {
        return [
            'accessControl' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'class' => AccessRule::class,
                        'permissions' => [static::TEST_PERMISSION],
                    ]
                ]
            ],
        ];
    }

    /**
     * @return array
     */
    protected function generateResponse(): array
    {
        return [];
    }
}
