<?php

namespace Wearesho\Yii\Http;

use yii\di;
use yii\caching;

/**
 * Class CachePanel
 * @package Wearesho\Yii\Http
 */
abstract class CachePanel extends Panel
{
    /** @var string|array|caching\Cache */
    public $cache = 'cache';

    /**
     * @see \yii\caching\Cache::set()
     * @var int
     */
    public $duration = 60;

    /**
     * @see \yii\caching\Cache::set()
     * @var caching\Dependency|null
     */
    public $dependency = null;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        $this->cache = di\Instance::ensure($this->cache, caching\Cache::class);
        if (!is_null($this->dependency)) {
            $this->dependency = di\Instance::ensure($this->dependency, caching\Dependency::class);
        }
    }

    final protected function generateResponse(): array
    {
        return $this->cache->getOrSet(
            $this->getCacheKey(),
            function (): array {
                return $this->generateRawResponse();
            },
            $this->duration,
            $this->dependency
        );
    }

    protected function getCacheKey(): array
    {
        /** @noinspection MissedFieldInspection */
        return [
            'class' => static::class,
            'type' => 'cache.panel',
            'attributes' => $this->getAttributes(),
        ];
    }

    abstract protected function generateRawResponse(): array;
}
