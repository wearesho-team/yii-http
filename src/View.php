<?php

namespace Wearesho\Yii\Http;

/**
 * Class View
 * @package Wearesho\Yii\Http
 */
abstract class View
{
    public static function render(...$args): array
    {
        /** @var static $instance */
        $instance = \Yii::$container->get(static::class, $args);
        return $instance->renderInstantiated();
    }

    public static function multiple(array $items, ...$args)
    {
        return \array_map(function (...$itemArgs) use ($args) {
            return static::render(...$args, ...$itemArgs);
        }, $items);
    }

    abstract protected function renderInstantiated(): array;
}
