<?php

namespace Wearesho\Yii\Http\Exceptions;

use yii\base;
use Horat1us\Yii;

/**
 * Class ValidationException
 * @package Wearesho\Yii\Http\Exceptions
 */
class HttpValidationException extends base\UserException implements Yii\Interfaces\ModelExceptionInterface
{
    use Yii\Traits\ModelExceptionTrait;
}
