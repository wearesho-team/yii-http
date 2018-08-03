<?php

namespace Wearesho\Yii\Http\Tests;

use PHPUnit\Framework\TestCase;

use yii\console\Application;

use yii\db\Migration;
use yii\db\Connection;

use \DirectoryIterator;
use yii\di\Container;

/**
 * Class AbstractTestCase
 * @package Wearesho\Yii\Tests
 */
abstract class AbstractTestCase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        if (file_exists($_ENV['DB_PATH'])) {
            unlink($_ENV['DB_PATH']);
        }
        file_put_contents($_ENV['DB_PATH'], '');
        chmod($_ENV['DB_PATH'], 0755);

        \Yii::$container = new Container();
        \Yii::$app = new Application($this->appConfig());
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Yii::$app = null;
        \Yii::$container = null;
    }

    protected function appConfig(): array
    {
        return [
            'id' => 'yii-register-confirmation-test',
            'basePath' => dirname(__DIR__),
            'components' => [
                'db' => [
                    'class' => Connection::class,
                    'dsn' => 'sqlite:' . $_ENV['DB_PATH'],
                ],
            ],
        ];
    }
}
