<?php

namespace Wearesho\Yii\Http\Rest;

/**
 * Interface SaveForm
 * @package Wearesho\Yii\Http\Rest
 */
interface SaveForm
{
    public const EVENT_BEFORE_SAVE = 'beforeSave';
    public const EVENT_AFTER_SAVE = 'afterSave';
}
