<?php

namespace Wearesho\Yii\Http;

use yii\web;

/**
 * Class Request
 * @package Wearesho\Yii\Http
 *
 * @property-read string $referrerBase
 */
class Request extends web\Request
{
    public $parsers = [
        'application/json' => web\JsonParser::class,
    ];

    public function getReferrerBase(): string
    {
        $urlParts = \parse_url($this->referrer);
        $isUrlFull = \array_key_exists('scheme', $urlParts)
            && \array_key_exists('host', $urlParts);

        if (!$isUrlFull) {
            return '';
        }

        $domain = "{$urlParts['scheme']}://{$urlParts['host']}";
        if (\array_key_exists('port', $urlParts)) {
            $domain .= ":{$urlParts['port']}";
        }

        return $domain;
    }
}
