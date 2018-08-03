<?php

namespace Wearesho\Yii\Http\Tests;

use Wearesho\Yii\Http;

use yii\web;
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

    /** @var web\User */
    protected $user;

    /** @var Http\Tests\Mocks\UserMock */
    protected $userMock;

    /** @var Http\Request */
    protected $request;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$authManager = \Yii::$app->authManager;
        static::$authManager->removeAll();
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
        $this->userMock = new Http\Tests\Mocks\UserMock(mt_rand());
        $this->request = new Http\Request([]);
    }

    public function testDeniedAccess(): void
    {
        $this->setRoleGuest();
        $this->setIdentityUser();

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
        $this->setRoleAdmin();
        $this->setIdentityUser();

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
        $this->setRoleAdmin();
        $this->setIdentityUser();

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
        $this->setRoleAdmin();
        $this->setIdentityUser();

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

    protected function setRole(string $roleName): void
    {
        $this->role = static::$authManager->createRole($roleName);
    }

    protected function setRoleGuest(): void
    {
        $this->setRole(static::ROLE_GUEST);
    }

    protected function setRoleAdmin(): void
    {
        $this->setRole(static::ROLE_ADMIN);
    }

    protected function setIdentityUser(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        static::$authManager->add($this->role);
        /** @noinspection PhpUnhandledExceptionInspection */
        static::$authManager->assign($this->role, $this->userMock->getId());
        \Yii::$app->user->setIdentity($this->userMock);
    }

    protected function tearDown(): void
    {
        static::$authManager->removeAll();
    }
}
