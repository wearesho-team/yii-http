<?php

namespace Wearesho\Yii\Http;

use Horat1us\Yii\Interfaces\ModelExceptionInterface;
use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use yii\web\ErrorHandler as WebErrorHandler;

/**
 * Class ErrorHandler
 * @package Wearesho\Yii\Http
 */
class ErrorHandler extends WebErrorHandler
{
    /**
     * @param \Error|\Exception $exception
     * @return array
     */
    protected function convertExceptionToArray($exception)
    {
        $exceptionArray = parent::convertExceptionToArray($exception);

        $shouldDisplayErrors = $exception instanceof ModelExceptionInterface
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
