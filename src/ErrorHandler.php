<?php

namespace Wearesho\Yii\Http;

use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use Horat1us\Yii\Validation;
use yii\web;

/**
 * Class ErrorHandler
 * @package Wearesho\Yii\Http
 */
class ErrorHandler extends web\ErrorHandler
{
    /**
     * @param \Error|\Exception $exception
     * @return array
     */
    protected function convertExceptionToArray($exception)
    {
        $exceptionArray = parent::convertExceptionToArray($exception);

        $shouldDisplayErrors = ($exception instanceof Validation\Failure)
            && ($exception instanceof HttpValidationException || YII_DEBUG);

        if ($shouldDisplayErrors) {
            $model = $exception->getModel();
            if (YII_DEBUG) {
                $exceptionArray['values'] = $model->getAttributes();
            }

            $exceptionArray['errors'] = [];
            foreach ($model->getErrors() as $attribute => $errors) {
                foreach ($errors as $details) {
                    $exceptionArray['errors'][] = [
                        'attribute' => $attribute,
                        'details' => $details,
                    ];
                }
            }
        }

        return $exceptionArray;
    }
}
