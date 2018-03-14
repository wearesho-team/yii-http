<?php

namespace Wearesho\Yii\Http\Rest;

use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use Wearesho\Yii\Http\Form;

/**
 * Class PutForm
 * @package Wearesho\Yii\Http\Rest
 */
class PutForm extends Form
{
    use RestPanelTrait;

    /**
     * @throws HttpValidationException
     * @return array
     */
    protected function generateResponse(): array
    {
        $this->record->scenario = $this->scenario;

        foreach ($this->record->activeAttributes() as $activeAttribute) {
            $this->record->{$activeAttribute} = null;
        }
        $this->record->load($this->request->bodyParams);

        HttpValidationException::saveOrThrow($this->record);

        return $this->record->toArray();
    }
}
