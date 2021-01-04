<?php declare(strict_types=1);

namespace Wearesho\Yii\Http\Tests\Behaviors;

use Wearesho\Yii\Http;

use yii\base\Action;
use yii\filters\AccessRule;
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

    protected Http\Panel $panelInstance;

    protected web\IdentityInterface  $user;

    protected rbac\Role $role;

    protected static rbac\ManagerInterface $authManager;

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
                                'roles' => [AccessControlTest::ROLE_ADMIN,],
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
                return ['key' => 'value'];
            }
        };
    }

    public function testCorrectAccess(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(
            ['key' => 'value'],
            $this->panelInstance->getResponse()->data
        );
    }

    public function testForbidden(): void
    {
        static::$authManager->revoke($this->role, $this->user->getId());

        $this->expectException(web\ForbiddenHttpException::class);
        $this->expectExceptionMessage('Action is not allowed.');
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->panelInstance->getResponse();
    }

    public function testCheckAccessWithDenyCallback(): void
    {
        $exceptionMessage = 'Exception in callback user function!';
        $control = new Http\Behaviors\AccessControl(
            new Http\Request(),
            [
                'user' => [
                    'identityClass' => Http\Tests\Mocks\UserMock::class,
                ],
                'denyCallback' => function ($i, Action $action) use ($exceptionMessage) {
                    throw new \Exception($exceptionMessage);
                }
            ]
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($exceptionMessage);
        $control->checkAccess();
    }

    public function testCheckAccessWithUserGuest(): void
    {
        $exceptionMessage = 'Exception from rule';
        $control = new Http\Behaviors\AccessControl(
            new Http\Request(),
            [
                'user' => [
                    'identityClass' => web\User::class,
                ],
                'rules' => [
                    new AccessRule([
                        'denyCallback' => function ($rule, Action $action) use ($exceptionMessage) {
                            throw new \Exception($exceptionMessage);
                        }
                    ])
                ]
            ]
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($exceptionMessage);
        $control->checkAccess();
    }

    public function testCheckAccessWithNullDenyCallback(): void
    {
        $exceptionMessage = 'Exception from deny callback';
        $control = new Http\Behaviors\AccessControl(
            new Http\Request(),
            [
                'user' => [
                    'identityClass' => web\User::class,
                ],
                'rules' => [
                    new AccessRule(['denyCallback' => null,]),
                ],
                'denyCallback' => function ($rule, Action $action) use ($exceptionMessage) {
                    throw new \Exception($exceptionMessage);
                },
            ]
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($exceptionMessage);
        $control->checkAccess();
    }

    protected function tearDown(): void
    {
        static::$authManager->removeAll();
    }
}
