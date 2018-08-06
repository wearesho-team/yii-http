<?php

namespace Wearesho\Yii\Http\Behaviors;

use Wearesho\Yii\Http;
use yii\base;

/**
 * Class ResponseFormat
 * @package Wearesho\Yii\Http\Behaviors
 */
class ResponseFormat extends base\Behavior
{
    /** @var string */
    public $format;

    /** @var Http\Response */
    protected $response;

    public function __construct(Http\Response $response, array $config = [])
    {
        parent::__construct($config);
        $this->response = $response;
    }

    public function events(): array
    {
        return [
            Http\Controller::EVENT_BEFORE_ACTION => 'setFormat',
        ];
    }

    public function setFormat(): void
    {
        if (!in_array($this->format, [
            Http\Response::FORMAT_HTML,
            Http\Response::FORMAT_JSON,
            Http\Response::FORMAT_JSONP,
            Http\Response::FORMAT_RAW,
            Http\Response::FORMAT_XML,
        ])) {
            throw new base\InvalidConfigException("Invalid response format {$this->format}");
        }

        $this->response->format = $this->format;
    }
}
