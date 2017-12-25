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

    abstract protected function renderInstantiated(): array;
}
