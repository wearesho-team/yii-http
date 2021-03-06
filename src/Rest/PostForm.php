<?php

namespace Wearesho\Yii\Http\Rest;

use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use Wearesho\Yii\Http\Form;
use yii\db\ActiveRecord;

/**
 * Class PostForm
 * @package Wearesho\Yii\Http\Rest
 */
class PostForm extends Form implements SaveForm
{
    use ScenarioTrait, ResponseConfigurable, SaveFormTrait;

    /** @var string ActiveRecord to be created */
    public $modelClass;

    /**
     * @throws HttpValidationException
     * @return array
     */
    protected function generateResponse(): array
    {
        /** @var ActiveRecord $record */
        $record = new $this->modelClass;
        $record->scenario = $this->scenario;

        $record->load($this->request->bodyParams);

        $this->save($record);

        return $this->convert($record);
    }
}
