<?php

namespace Wearesho\Yii\Http\Rest;

use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use Wearesho\Yii\Http\Rest\RestPanelTrait;

/**
 * Class PatchPanel
 * @package Wearesho\Yii\Http
 */
class PatchForm extends Form
{
    use RestPanelTrait;

    /**
     * @throws HttpValidationException
     * @return array
     */
    protected function generateResponse(): array
    {
        $this->record->scenario = $this->scenario;
        $this->record->load($this->request->bodyParams);

        HttpValidationException::saveOrThrow($this->record);

        return $this->record->toArray($this->record->activeAttributes());
    }
}
