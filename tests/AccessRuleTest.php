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
        $object = new AccessRule([
        ]);
        $object->permissions = function () {
            return [];
        };


        $this->assertEquals(
            null,
            $object->allows(
                new Action(
                    "id_action",
                    new Controller(
                        "is_controller",
                        new Module("id_module")
                    ),
                    []
                ),
                new User(
                    [
                        'identityClass' => "",
                    ]
                ),
                new Request([])
            )
        );
    }
}
