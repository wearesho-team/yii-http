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
    protected static $access;

    /** @var Http\Action */
    protected static $action;

    /** @var web\User */
    protected static $user;

    /** @var Http\Request */
    protected static $request;

    public function testDeniedAccess(): void
    {
        $this->assertFalse(
            static::$access->allows(
                static::$action,
                static::$user,
                static::$request
            )
        );
    }

    public function testAcceptAccess(): void
    {
        static::$access = new Http\AccessRule([
            'permissions' => [],
            'allow' => true
        ]);

        $this->assertTrue(
            static::$access->allows(
                static::$action,
                static::$user,
                static::$request
            )
        );
    }

    public static function setUpBeforeClass()
    {
        static::$access = new Http\AccessRule([
            'permissions' => function (): array {
                return [];
            },
        ]);
        static::$action = new Http\Action(
            "id_action",
            new Mocks\Access\TestController(
                "id_controller",
                new base\Module("id_module")
            ),
            []
        );
        static::$user = new web\User([
            'identityClass' => "AndrewClass",
        ]);
        static::$request = new Http\Request([]);
    }
}
