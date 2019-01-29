<?php  // phpcs:ignore

\Yii::setAlias('@Wearesho/Yii/Http', \dirname(__DIR__, 3));

$envPath = __DIR__ . DIRECTORY_SEPARATOR . 'Environment' . DIRECTORY_SEPARATOR . 'Dev';
$env = '.env';

if (\file_exists($envPath . DIRECTORY_SEPARATOR . $env)) {
    $dotEnv = \Dotenv\Dotenv::create($envPath, $env)->load();
}

\Yii::setAlias('@Wearesho/Yii/Http', \dirname(__DIR__));
\Yii::setAlias('output', __DIR__ . '/output');
\Yii::setAlias(
    '@configFile',
    \dirname(__DIR__) . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'config.php'
);
