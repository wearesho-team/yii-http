<?php


namespace Wearesho\Yii\Http\Behaviors;


use Wearesho\Yii\Http\Panel;

use yii\base\Behavior;
use yii\rbac\ManagerInterface;
use yii\web\ForbiddenHttpException;
use yii\web\IdentityInterface;


/**
 * Class AccessControl
 * @package Wearesho\Yii\Http\Behaviors
 */
class AccessControl extends Behavior
{
    /** @var string|string[] */
    public $permissions;

    /** @var callable returns IdentityInterface */
    public $user;

    /** @var ManagerInterface */
    protected $manager;

    /**
     * AccessControl constructor.
     * @param ManagerInterface $manager
     * @param array $config
     */
    public function __construct(ManagerInterface $manager, array $config = [])
    {
        parent::__construct($config);
        $this->manager = $manager;
    }


    public function events()
    {
        return [
            Panel::EVENT_BEFORE_VALIDATE => 'checkAccess',
        ];
    }

    public function init()
    {
        parent::init();
        if (!is_callable($this->user)) {
            $this->user = function (): IdentityInterface {
                return \Yii::$app->user->getIdentity();
            };
        }
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function checkAccess()
    {
        /** @var IdentityInterface $user */
        $user = call_user_func($this->user);
        foreach ((array)$this->permissions as $permission) {
            if ($this->manager->checkAccess($user->getId(), $permission)) {
                return;
            }
        }
        throw new ForbiddenHttpException();
    }
}