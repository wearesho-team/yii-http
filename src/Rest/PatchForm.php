<?php

namespace Wearesho\Yii\Http\Rest;

use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use Wearesho\Yii\Http\Form;

/**
 * Class PatchPanel
 * @package Wearesho\Yii\Http
 */
class PatchForm extends Form implements SaveForm
{
    use RestPanelTrait, ScenarioTrait, ResponseConfigurable, SaveFormTrait;

    /**
     * @throws HttpValidationException
     * @return array
     */
    protected function generateResponse(): array
    {
        $this->record->scenario = $this->scenario;
        $this->record->load($this->request->bodyParams);

        $this->save($this->record);

        return $this->convert($this->record);
    }
}
