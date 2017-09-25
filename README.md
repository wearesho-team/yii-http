## Yii2 Http

Alternative work with HTTP

### Installation
```bash
composer require wearesho-team/yii-tokens
```
Add to your DI container:
```php
<?php
\Yii::$container->setSingleton(
    \yii\web\Response::class,
    \Wearesho\Yii\Http\Response::class
);
\Yii::$container->set(
    \yii\web\ErrorHandler::class,
    \Wearesho\Yii\Http\ErrorHandler::class
);
\Yii::$container->setSingleton(\yii\db\Connection::class);
```

### TODO
1. Documentation
2. Tests