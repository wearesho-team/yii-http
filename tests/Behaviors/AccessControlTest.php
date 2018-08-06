<?php

namespace Wearesho\Yii\Http\Tests\Behaviors;

use Wearesho\Yii\Http;

use yii\rbac;
use yii\web;

/**
 * Class AccessControlTest
 * @package Wearesho\Yii\Http\Tests\Behaviors
 *
 * @internal
 */
class AccessControlTest extends Http\Tests\AbstractTestCase
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_GUEST = 'guest';

    /** @var Http\Panel */
    protected $panelInstance;

    /** @var web\IdentityInterface */
    protected $user;

    /** @var rbac\Role */
    protected $role;

    /** @var rbac\ManagerInterface */
    protected static $authManager;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$authManager = \Yii::$app->authManager;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new Http\Tests\Mocks\UserMock(mt_rand());
        $this->role = static::$authManager->createRole(static::ROLE_ADMIN);
        /** @noinspection PhpUnhandledExceptionInspection */
        static::$authManager->add($this->role);
        /** @noinspection PhpUnhandledExceptionInspection */
        static::$authManager->assign($this->role, $this->user->getId());
        \Yii::$app->user->setIdentity($this->user);

        $this->panelInstance = new class($this->user) extends Http\Panel
        {
            /** @var web\IdentityInterface */
            public $user;

            public function __construct(web\IdentityInterface &$user)
            {
                parent::__construct(new Http\Request(), new Http\Response(), []);
                $this->user = &$user;
            }

            public function formName()
            {
                return 'AnonymousForm';
            }

            public function behaviors()
            {
                return [
                    'access' => [
                        'class' => Http\Behaviors\AccessControl::class,
                        'rules' => [
                            [
                                'roles' => [AccessControlTest::ROLE_ADMIN, ],
                            ]
                        ],
                        'user' => [
                            'class' => web\User::class,
                            'identityClass' => get_class($this->user),
                            'identity' => $this->user,
                        ]
                    ],
                ];
            }

            /**
             * @throws \Exception
             * @return array
             */
            protected function generateResponse(): array
            {
                throw new \Exception("Method not implemented");
            }
        };
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Method not implemented
     */
    public function testCorrectAccess(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->panelInstance->getResponse();
    }

    /**
     * @expectedException \yii\web\ForbiddenHttpException
     * @expectedExceptionMessage Action is not allowed.
     */
    public function testForbidden(): void
    {
        static::$authManager->revoke($this->role, $this->user->getId());

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->panelInstance->getResponse();
    }

    protected function tearDown(): void
    {
        static::$authManager->removeAll();
    }
}
