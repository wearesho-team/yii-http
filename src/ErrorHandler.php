<?php

namespace Wearesho\Yii\Http;

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
        if ($exception instanceof HttpValidationException) {
            $exceptionArray['errors'] = [];
            foreach ($exception->getModel()->getErrors() as $attribute => $errors) {
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
