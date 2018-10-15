<?php

namespace Wearesho\Yii\Http\Rest;

use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use yii\base;
use yii\db;

/**
 * Trait SaveFormTrait
 * @package Wearesho\Yii\Http\Rest
 * @method trigger(string $name, base\Event $event)
 */
trait SaveFormTrait
{

    /**
     * @param db\ActiveRecordInterface $model
     * @throws HttpValidationException
     */
    protected function save(db\ActiveRecordInterface $model): void
    {
        $this->trigger(SaveForm::EVENT_BEFORE_SAVE, new base\Event(['sender' => $model]));

        /** @noinspection PhpUnhandledExceptionInspection */
        HttpValidationException::saveOrThrow($model);

        $this->trigger(SaveForm::EVENT_AFTER_SAVE, new base\Event(['sender' => $model]));
    }
}
