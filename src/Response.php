<?php

namespace Wearesho\Yii\Http;

use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use yii\web;

/**
 * Class Response
 * @package Wearesho\Yii\Http
 */
class Response extends web\Response
{
    public $format = self::FORMAT_JSON;

    /**
     * @param \Error|\Exception $e
     * @return $this|web\Response
     */
    public function setStatusCodeByException($e)
    {
        if ($e instanceof HttpValidationException) {
            $this->setStatusCode(400);
            return $this;
        }

        return parent::setStatusCodeByException($e);
    }
}
