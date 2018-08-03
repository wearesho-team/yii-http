<?php

namespace Wearesho\Yii\Http\Tests;

use Wearesho\Yii\Http;

use yii\base;
use yii\web;

class AccessRuleTest extends AbstractTestCase
{
    /** @var Http\AccessRule */
    protected $access;

    /** @var Http\Action */
    protected $action;

    /** @var web\User */
    protected $user;

    /** @var Http\Request */
    protected $request;

    public function testDeniedAccess(): void
    {
        $this->access = new Http\AccessRule([
            'permissions' => function (): array {
                return [];
            },
        ]);

        $this->action = new Http\Action(
            "id_action",
            new Mocks\Access\TestController(
                "id_controller",
                new base\Module("id_module")
            ),
            []
        );
        $this->user = new web\User([
            'identityClass' => "AndrewClass",
        ]);
        $this->request = new Http\Request([]);

        $this->assertFalse(
            $this->access->allows(
                $this->action,
                $this->user,
                $this->request
            )
        );
    }
}
