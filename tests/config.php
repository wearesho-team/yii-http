<?php

use yii\helpers\ArrayHelper;
use yii\db\Connection;
use yii\rbac\PhpManager;
use yii\web\User;
use Wearesho\Yii\Http\Tests\Mocks\UserMock;
use Wearesho\Yii\Http\Response;

$localConfig = __DIR__ . DIRECTORY_SEPARATOR . 'config-local.php';

$dbType = \getenv('DB_TYPE');
$host = \getenv('DB_HOST');
$name = \getenv("DB_NAME");
$port = \getenv("DB_PORT");

$dsn = "{$dbType}:host={$host};dbname={$name};port={$port}";

$config = [
    'id' => 'yii-http',
    'basePath' => \dirname(__DIR__),
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => $dsn,
            'username' => \getenv("DB_USERNAME"),
            'password' => \getenv("DB_PASSWORD") ?: null,
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
        ],
        'response' => [
            'class' => Response::class,
        ],
    ],
];

return ArrayHelper::merge(
    $config,
    \is_file($localConfig) ? require $localConfig : []
);
