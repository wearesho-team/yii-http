<?php

namespace Wearesho\Yii\Http\Rest;

use Wearesho\Yii\Http\Request;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * Class ResetPanel
 * @package Wearesho\Yii\Http
 *
 * @property-read Request $request
 * @property-read string $id
 */
trait RestPanelTrait
{
    /** @var string Class of ActiveRecord */
    public $modelClass;

    /** @var callable|array ActiveQuery filter */
    public $filter;

    /** @var ActiveRecord */
    public $record;

    public function getId()
    {
        return $this->request->get('id');
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            /** @var ActiveRecord|null $record */
            $this->record = $this->filter(\call_user_func([$this->modelClass, 'find']))->one();
            if (!$this->record instanceof $this->modelClass) {
                throw new NotFoundHttpException("Resource #{$this->id} not found!");
            }

            return true;
        }
        return false;
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    protected function filter(ActiveQuery $query): ActiveQuery
    {
        $primaryKey = \call_user_func([$this->modelClass, 'primaryKey']);
        if (!\array_key_exists(0, $primaryKey)) {
            throw new InvalidConfigException("Can not use {$this->modelClass} that have no primary key");
        }

        $query->andWhere(['=', $primaryKey[0], $this->id]);

        if ($this->filter instanceof \Closure || \is_array($this->filter) && \is_callable($this->filter)) {
            \call_user_func($this->filter, $query);
        } elseif (\is_array($this->filter)) {
            $query->andWhere($this->filter);
        } elseif (!\is_null($this->filter)) {
            throw new InvalidConfigException("Filter should be query array or callable that receives query");
        }

        return $query;
    }
}
