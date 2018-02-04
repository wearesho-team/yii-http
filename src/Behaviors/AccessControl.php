<?php

namespace Wearesho\Yii\Http\Behaviors;

use Wearesho\Yii\Http\Controller;

use Wearesho\Yii\Http\Request;
use yii\base\Action;
use yii\base\Behavior;
use yii\base\Module;
use yii\di\Instance;
use yii\filters\AccessRule;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\User;

/**
 * Class AccessControl
 * @package Wearesho\Yii\Http\Behaviors
 */
class AccessControl extends Behavior
{
    public const ROLE_DEFAULT = '@';
    public const ROLE_GUEST = '?';

    /**
     * @var array the default configuration of access rules. Individual rule configurations
     * specified via [[rules]] will take precedence when the same property of the rule is configured.
     */
    public $ruleConfig = [
        'class' => AccessRule::class,
        'allow' => true,
    ];

    /**
     * @var array a list of access rule objects or configuration arrays for creating the rule objects.
     * If a rule is specified via a configuration array, it will be merged with [[ruleConfig]] first
     * before it is used for creating the rule object.
     * @see ruleConfig
     */
    public $rules = [];

    /** @var User|array */
    public $user = [
        'class' => \yii\web\User::class,
    ];

    /** @var callable */
    public $denyCallback;

    /** @var Request */
    protected $request;

    public function __construct(Request $request, array $config = [])
    {
        parent::__construct($config);
        $this->request = $request;
    }


    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'checkAccess',
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->user = Instance::ensure($this->user, \yii\web\User::class);
        foreach ($this->rules as $i => $rule) {
            if (is_array($rule)) {
                $config = array_merge($this->ruleConfig, $rule);
                $this->rules[$i] = \Yii::createObject($config);
            }
        }
    }

    /**
     * @throws HttpException
     */
    public function checkAccess(): void
    {
        $user = $this->user;
        $action = new Action(
            get_called_class(),
            new \yii\base\Controller(
                'fake-controller',
                new Module('fake-module')
            )
        );

        /* @var $rule AccessRule */
        foreach ($this->rules as $rule) {
            if ($allow = $rule->allows($action, $user, $this->request)) {
                return;
            } elseif ($allow === false) {
                if (isset($rule->denyCallback)) {
                    call_user_func($rule->denyCallback, $rule, $action);
                } elseif ($this->denyCallback !== null) {
                    call_user_func($this->denyCallback, $rule, $action);
                }

                $this->deny($user);
            }
        }

        if ($this->denyCallback !== null) {
            call_user_func($this->denyCallback, null, $action);
        }
        $this->deny($user);
    }

    /**
     * @param User $user
     *
     * @throws UnauthorizedHttpException
     * @throws ForbiddenHttpException
     */
    protected function deny(User $user): void
    {
        if ($user->getIsGuest()) {
            try {
                $user->loginRequired();
            } catch (ForbiddenHttpException $ex) {
                throw new UnauthorizedHttpException($ex->getMessage(), $ex->getCode(), $ex);
            }
        }
        throw new ForbiddenHttpException("Action is not allowed.");
    }
}
