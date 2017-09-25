<?php


namespace Wearesho\Yii\Http\Exceptions;

use Throwable;

use yii\base\Model;
use yii\base\UserException;

use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;

/**
 * Class ValidationException
 * @package Wearesho\Yii\Http\Exceptions
 */
class ValidationException extends UserException
{
    /** @var Model */
    protected $model;

    /**
     * ValidationException constructor.
     * @param Model $model
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(Model $model, $code = 0, Throwable $previous = null)
    {
        $message = "Validation error";
        parent::__construct($message, $code, $previous);

        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param Model $model
     * @return Model
     */
    public static function validateOrThrow(Model $model): Model
    {
        if (!$model->validate()) {
            throw new static($model);
        }
        return $model;
    }

    /**
     * @param ActiveRecordInterface $record
     * @return ActiveRecordInterface|ActiveRecord
     */
    public static function saveOrThrow(ActiveRecordInterface $record): ActiveRecordInterface
    {
        if (!$record->save() && $record instanceof Model) {
            throw new static($record);
        }
        return $record;
    }

    /**
     * @param string $attribute
     * @param string $error
     * @param Model $model
     */
    public static function addAndThrow(string $attribute, string $error, Model $model)
    {
        $model->addError($attribute, $error);
        throw new static($model);
    }
}