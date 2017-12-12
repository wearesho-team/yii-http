<?php


namespace Wearesho\Yii\Http;

use Wearesho\Yii\Http\Exceptions\HttpValidationException;
use yii\web\Response as WebResponse;

/**
 * Class Response
 * @package Wearesho\Yii\Http
 */
class Response extends WebResponse
{
    public $format = self::FORMAT_JSON;

    /**
     * @param \Error|\Exception $e
     * @return $this|\yii\web\Response
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