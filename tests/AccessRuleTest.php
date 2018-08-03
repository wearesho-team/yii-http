<?php

namespace Wearesho\Yii\Http\Tests;

use Wearesho\Yii\Http;

use yii\base;
use yii\rbac;

/**
 * Class AccessRuleTest
 * @package Wearesho\Yii\Http\Tests
 *
 * @internal
 */
class AccessRuleTest extends AbstractTestCase
{
    protected const ROLE_ADMIN = 'admin';
    protected const ROLE_GUEST = 'guest';

    /** @var rbac\ManagerInterface */
    protected static $authManager;

    /** @var rbac\Role */
    protected $role;

    /** @var Http\AccessRule */
    protected $access;

    /** @var Http\Action */
    protected $action;

    /** @var Http\Tests\Mocks\UserMock */
    protected $user;

    /** @var Http\Request */
    protected $request;

    public function testDeniedAccess(): void
    {
        $this->role = static::$authManager->createRole(static::ROLE_GUEST);
        $user = new Http\Tests\Mocks\UserMock(mt_rand());
        /** @noinspection PhpUnhandledExceptionInspection */
        static::$authManager->add($this->role);
        /** @noinspection PhpUnhandledExceptionInspection */
        static::$authManager->assign($this->role, $user->getId());
        \Yii::$app->user->setIdentity($user);

        $this->access = new Http\AccessRule([
            'allow' => false,
            'permissions' => [
                static::ROLE_GUEST
            ]
        ]);

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
        $this->role = static::$authManager->createRole(static::ROLE_ADMIN);
        $user = new Http\Tests\Mocks\UserMock(mt_rand());
        /** @noinspection PhpUnhandledExceptionInspection */
        static::$authManager->add($this->role);
        /** @noinspection PhpUnhandledExceptionInspection */
        static::$authManager->assign($this->role, $user->getId());
        \Yii::$app->user->setIdentity($user);

        $this->access = new Http\AccessRule([
            'allow' => true,
            'permissions' => [
                static::ROLE_ADMIN,
                static::ROLE_GUEST
            ]
        ]);

        $this->assertTrue(
            $this->access->allows(
                $this->action,
                $this->user,
                $this->request
            )
        );
    }

    public function testNullAccess(): void
    {
        $this->role = static::$authManager->createRole(static::ROLE_ADMIN);
        $user = new Http\Tests\Mocks\UserMock(mt_rand());
        /** @noinspection PhpUnhandledExceptionInspection */
        static::$authManager->add($this->role);
        /** @noinspection PhpUnhandledExceptionInspection */
        static::$authManager->assign($this->role, $user->getId());
        \Yii::$app->user->setIdentity($user);

        $this->access = new Http\AccessRule([
            'allow' => true,
            'permissions' => [
                static::ROLE_GUEST
            ]
        ]);

        $this->assertNull(
            $this->access->allows(
                $this->action,
                $this->user,
                $this->request
            )
        );
    }

    public function testCallablePermissions(): void
    {
        $this->role = static::$authManager->createRole(static::ROLE_ADMIN);
        $user = new Http\Tests\Mocks\UserMock(mt_rand());
        /** @noinspection PhpUnhandledExceptionInspection */
        static::$authManager->add($this->role);
        /** @noinspection PhpUnhandledExceptionInspection */
        static::$authManager->assign($this->role, $user->getId());
        \Yii::$app->user->setIdentity($user);

        $this->access = new Http\AccessRule([
            'allow' => true,
            'permissions' => function (): array {
                return [
                    static::ROLE_ADMIN,
                    static::ROLE_GUEST
                ];
            }
        ]);

        $this->assertTrue(
            $this->access->allows(
                $this->action,
                $this->user,
                $this->request
            )
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new Http\Action(
            'id_action',
            new Http\Controller(
                'id_controller',
                new base\Module('id_module')
            ),
            []
        );
        $this->user = \Yii::$app->user;
        $this->request = new Http\Request([]);
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$authManager = \Yii::$app->authManager;
        static::$authManager->removeAll();
    }

    protected function tearDown(): void
    {
        static::$authManager->removeAll();
    }
}
