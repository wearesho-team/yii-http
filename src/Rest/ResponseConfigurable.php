<?php

namespace Wearesho\Yii\Http\Rest;

use yii\base;

/**
 * Trait ResponseConfigurable
 * @package Wearesho\Yii\Http\Rest
 */
trait ResponseConfigurable
{

    /**
     * @var array
     * @see ArrayableTrait::toArray()
     */
    public $fields = [];

    /**
     * @var array
     * @see ArrayableTrait::toArray()
     */
    public $expand = [];

    /**
     * @var bool
     */
    public $recursive = true;

    protected function convert(base\Model $model): array
    {
        return $model->toArray($this->fields, $this->expand, $this->recursive);
    }
}
