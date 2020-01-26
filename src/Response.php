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
    public const FORMAT_DEFAULT = self::FORMAT_JSON;

    /**
     * @var string|null
     * @see isVaryFormat()
     */
    public $format = null;

    public $cacheControl = 'private, no-cache, max-age=604800';

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

    public function isVaryFormat(): bool
    {
        return !is_null($this->format);
    }

    public function isVaryLanguage(): string
    {
        return \Yii::$app->has('i18n', true) && !\Yii::$app->has('i18n', false);
    }

    public function hasETag(): bool
    {
        $verb = \Yii::$app->request;
        return $this->getIsSuccessful()
            && is_string($this->cacheControl) && $verb !== 'GET' && $verb !== 'HEAD'
            && (is_null($this->stream) || !empty($this->content));
    }

    protected function prepare()
    {
        $this->prepareFormat();;

        parent::prepare();

        if ($this->hasETag() && $this->prepareEtag() === \Yii::$app->request->headers->get('If-None-Match')) {
            $this->statusCode = 304;
            $this->content = null;
        }
    }

    protected function prepareFormat(): void
    {
        if ($this->isVaryFormat()) {
            $this->headers->add('Vary', 'Accept');
        } else {
            $this->format = static::FORMAT_DEFAULT;
        }
    }

    protected function prepareEtag(): string
    {
        $vary = $this->headers->get('Vary', [], false);
        if ($this->isVaryLanguage()) {
            $this->headers->set('Vary', array_unique([...$vary, 'Accept-Language']));
        } else {
            $this->headers->set('Vary', array_diff($vary, ['Accept-Language']));
        }
        $this->headers->setDefault('Cache-Control', $this->cacheControl);
        $eTag = $this->headers->get('ETag');
        if (is_null($eTag)) {
            $eTag = 'W/"' . hash('crc32', $this->content) . '"';
            $this->headers->set('ETag', $eTag);
        }
        return $eTag;
    }
}
