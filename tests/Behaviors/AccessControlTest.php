<?php

namespace Wearesho\Yii\Http\Tests\Behaviors;

use Wearesho\Yii\Http\Behaviors\AccessControl;
use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use Wearesho\Yii\Http\Panel;
use Wearesho\Yii\Http\Request;
use Wearesho\Yii\Http\Response;
use Wearesho\Yii\Http\Tests\AbstractTestCase;
use yii\rbac\ManagerInterface;
use yii\rbac\Permission;
use yii\rbac\PhpManager;
use yii\rbac\Role;
use yii\web\ForbiddenHttpException;
use yii\web\IdentityInterface;
use yii\web\User;

/**
 * Class AccessControlTest
 * @package Wearesho\Yii\Http\Tests\Behaviors
 * @internal
 */
class AccessControlTest extends AbstractTestCase
{

    /** @var Panel */
    protected $panelInstance;

    /** @var IdentityInterface */
    protected $user;

    /** @var ManagerInterface */
    protected $manager;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    protected function setUp()
    {
        parent::setUp();

        $this->user = new class(mt_rand(1, 100)) implements IdentityInterface
        {

            private $id;

            public function __construct(int $id)
            {
                $this->id = $id;
            }

            public static function findIdentity($id)
            {
            }

            public static function findIdentityByAccessToken($token, $type = null)
            {
            }

            public function getId()
            {
                return $this->id;
            }

            public function getAuthKey()
            {
            }

            public function validateAuthKey($authKey)
            {
            }
        };

        $this->panelInstance = new class($this->user) extends Panel
        {
            /** @var IdentityInterface */
            public $user;

            public function __construct(IdentityInterface &$user)
            {
                parent::__construct(new Request(), new Response(), []);
                $this->user = &$user;
            }

            public function behaviors()
            {
                return [
                    'access' => [
                        'class' => AccessControl::class,
                        'rules' => [
                            [
                                'roles' => ['test'],
                            ]
                        ],
                        'user' => [
                            'class' => User::class,
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

    protected function appConfig(): array
    {
        $parentAppConfig = parent::appConfig();
        \Yii::$container->set(
            ManagerInterface::class,
            [
                'class' => PhpManager::class,
                'itemFile' => '@output/items.php',
                'assignmentFile' => '@output/assignment.php',
                'ruleFile' => '@output/rule.php',
            ]
        );

        $this->manager = \Yii::$container->get(ManagerInterface::class);
        $config = [
            'components' => [
                'authManager' => $this->manager,
            ],
        ];

        return array_merge($parentAppConfig, $config);
    }

    /**
     * @throws \Exception
     */
    public function testCorrect()
    {
        $role = $this->getRole('test');

        $this->manager->getAssignment('test', $this->user->getId())
        || $this->manager->assign($role, $this->user->getId());

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Method not implemented");

        $this->panelInstance->getResponse();
    }

    public function testForbidden()
    {
        $role = $this->manager->getRole('test');
        if ($role instanceof Role) {
            $this->manager->revoke($role, $this->user->getId());
        }

        $this->expectException(ForbiddenHttpException::class);

        $this->panelInstance->getResponse();
    }

    protected function getRole(string $roleName)
    {
        $role = $this->manager->getRole($roleName);

        if (!$role) {
            $role = $this->manager->createRole($roleName);
            $this->manager->add($role);
        }

        return $role;
    }
}
