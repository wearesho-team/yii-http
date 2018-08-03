<?php

namespace Wearesho\Yii\Http\Tests;

use Wearesho\Yii\Http;

use yii\base;
use yii\web;

/**
 * Class AccessRuleTest
 * @package Wearesho\Yii\Http\Tests
 *
 * @internal
 */
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
        $this->assertFalse(
            $this->access->allows(
                $this->action,
                $this->user,
                $this->request
            )
        );
    }

    public function testAcceptAccess(): void
    {
        $this->access = new Http\AccessRule([
            'permissions' => [],
            'allow' => true
        ]);

        $this->assertTrue(
            $this->access->allows(
                $this->action,
                $this->user,
                $this->request
            )
        );
    }

    public function setUp(): void
    {
        $this->access = new Http\AccessRule([
            'permissions' => function (): array {
                return [];
            },
        ]);
        $this->action = new Http\Action(
            "id_action",
            new Http\Controller(
                "id_controller",
                new base\Module("id_module")
            ),
            []
        );
        $this->user = new web\User([
            'identityClass' => "AndrewClass",
        ]);
        $this->request = new Http\Request([]);
    }
}
