<?php

namespace Wearesho\Yii\Http;

use yii\filters\AccessRule as YiiAccessRule;

/**
 * Class AccessRule
 * @package Wearesho\Yii\Http
 */
class AccessRule extends YiiAccessRule
{
    /**
     * This implementation pass callable as value
     * Callable will be called each time in @see allows() method
     *
     * @inheritdoc
     *
     * @var array|callable
     */
    public $permissions;

    /**
     * @inheritdoc
     */
    public function allows($action, $user, $request)
    {
        if (!empty($this->permissions) && is_callable($this->permissions)) {
            $permissions = $this->permissions;
            $this->permissions = call_user_func($this->permissions);
        }

        $result = parent::allows($action, $user, $request);
        !empty($permissions) && $this->permissions = $permissions;

        return $result;
    }
}
