<?php

namespace Wearesho\Yii\Http\Rest;

use Wearesho\Yii\Http\Form;
use yii\web\UnprocessableEntityHttpException;

class DeleteForm extends Form
{
    use RestPanelTrait;

    /**
     * @return array
     * @throws UnprocessableEntityHttpException
     */
    protected function generateResponse(): array
    {
        try {
            $this->record->delete();
        } catch (\Throwable $exception) {
            throw new UnprocessableEntityHttpException("Can not delete resource #{$this->id}");
        }

        return [];
    }
}
