<?php

namespace Wearesho\Yii\Http\Tests\Unit;

use yii\phpunit;
use yii\helpers;

/**
 * Class TestCase
 * @package Wearesho\Yii\Http\Tests\Unit
 */
class TestCase extends phpunit\TestCase
{
    public function globalFixtures(): array
    {
        $fixtures = [
            [
                'class' => phpunit\MigrateFixture::class,
                'migrationNamespaces' => [
                    'Wearesho\\Yii\\Http\\tests\\Migration',
                ],
            ]
        ];

        return helpers\ArrayHelper::merge(parent::globalFixtures(), $fixtures);
    }
}
