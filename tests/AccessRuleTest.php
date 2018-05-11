<?php

namespace Wearesho\Yii\Http\Tests;

use Wearesho\Yii\Http\AccessRule;
use Wearesho\Yii\Http\Action;
use Wearesho\Yii\Http\Controller;
use Wearesho\Yii\Http\Request;
use yii\base\Module;
use yii\web\User;

class AccessRuleTest extends AbstractTestCase
{
    public function testAllows()
    {
        $accessRule1 = new AccessRule();
        $accessRule1->permissions = function () {
            return [];
        };

        $this->assertFalse(
            $accessRule1->allows(
                new Action(
                    "oneAction",
                    new Controller(
                        "oneController",
                        new Module("oneModule")
                    ),
                    []
                ),
                new User(
                    [
                        'identityClass' => "AndrewClass",
                    ]
                ),
                new Request([])
            )
        );
    }
}
