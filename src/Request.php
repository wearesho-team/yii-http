<?php

namespace Wearesho\Yii\Http;

use yii\web\JsonParser;
use yii\web\Request as WebRequest;

/**
 * Class Request
 * @package Wearesho\Yii\Http
 */
class Request extends WebRequest
{
    public $parsers = [
        'application/json' => JsonParser::class,
    ];
}
