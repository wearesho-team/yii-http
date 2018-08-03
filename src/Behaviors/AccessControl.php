<?php

namespace Wearesho\Yii\Http\Behaviors;

use Wearesho\Yii\Http;
use yii\base;
use yii\di;
use yii\web;
use yii\filters;

/**
 * Class AccessControl
 * @package Wearesho\Yii\Http\Behaviors
 */
class AccessControl extends base\Behavior
{
    public const ROLE_DEFAULT = '@';
    public const ROLE_GUEST = '?';

    /**
     * @var array the default configuration of access rules. Individual rule configurations
     * specified via [[rules]] will take precedence when the same property of the rule is configured.
     */
    public $ruleConfig = [
        'class' => filters\AccessRule::class,
        'allow' => true,
    ];

    /**
     * @var array a list of access rule objects or configuration arrays for creating the rule objects.
     * If a rule is specified via a configuration array, it will be merged with [[ruleConfig]] first
     * before it is used for creating the rule object.
     * @see ruleConfig
     */
    public $rules = [];

    /** @var web\User|array */
    public $user = [
        'class' => web\User::class,
    ];

    /** @var callable */
    public $denyCallback;

    /** @var Http\Request */
    protected $request;

    public function __construct(Http\Request $request, array $config = [])
    {
        parent::__construct($config);
        $this->request = $request;
    }


    public function events()
    {
        return [
            Http\Panel::EVENT_BEFORE_VALIDATE => 'checkAccess',
        ];
    }

    /**
     * @throws base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->user = di\Instance::ensure($this->user, web\User::class);
        foreach ($this->rules as $i => $rule) {
            if (is_array($rule)) {
                $config = array_merge($this->ruleConfig, $rule);
                $this->rules[$i] = \Yii::createObject($config);
            }
        }
    }

    /**
     * @throws web\HttpException
     */
    public function checkAccess(): void
    {
        $user = $this->user;
        $action = new base\Action(
            get_called_class(),
            new base\Controller(
                'fake-controller',
                new base\Module('fake-module')
            )
        );

        /* @var $rule filters\AccessRule */
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
     * @param web\User $user
     *
     * @throws web\UnauthorizedHttpException
     * @throws web\ForbiddenHttpException
     */
    protected function deny(web\User $user): void
    {
        if ($user->getIsGuest()) {
            try {
                $user->loginRequired();
            } catch (web\ForbiddenHttpException $ex) {
                throw new web\UnauthorizedHttpException($ex->getMessage(), $ex->getCode(), $ex);
            }
        }
        throw new web\ForbiddenHttpException("Action is not allowed.");
    }
}
