<?php

namespace Wearesho\Yii\Http\Tests;

use PHPUnit\Framework\TestCase;

use Wearesho\Yii\Http\Tests\Mocks\UserMock;

use yii\console\Application;
use yii\db\Connection;
use yii\di\Container;
use yii\rbac\PhpManager;
use yii\web\User;

/**
 * Class AbstractTestCase
 * @package Wearesho\Yii\Tests
 */
abstract class AbstractTestCase extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        static::setUpConfig();
    }

    protected function setUp(): void
    {
        static::setUpConfig();
    }

    protected static function setUpConfig(): void
    {
        if (file_exists($_ENV['DB_PATH'])) {
            unlink($_ENV['DB_PATH']);
        }

        file_put_contents($_ENV['DB_PATH'], '');
        chmod($_ENV['DB_PATH'], 0755);

        \Yii::$container = new Container();
        /** @noinspection PhpUnhandledExceptionInspection */
        \Yii::$app = new Application(static::appConfig());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        \Yii::$app = null;
        \Yii::$container = null;
    }

    protected static function appConfig(): array
    {
        return [
            'id' => 'yii-register-confirmation-test',
            'basePath' => dirname(__DIR__),
            'components' => [
                'db' => [
                    'class' => Connection::class,
                    'dsn' => 'sqlite:' . $_ENV['DB_PATH'],
                ],
                'authManager' => [
                    'class' => PhpManager::class,
                    'itemFile' => '@output/items.php',
                    'assignmentFile' => '@output/assignment.php',
                    'ruleFile' => '@output/rule.php',
                ],
                'user' => [
                    'class' => User::class,
                    'identityClass' => UserMock::class,
                    'enableSession' => false,
                ]
            ],
        ];
    }
}
