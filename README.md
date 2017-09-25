## Yii2 Http

Alternative work with HTTP

### Installation
```bash
composer require wearesho-team/yii-tokens
```
Add to your config:
```php
common/config/main.php
<?php
return [
    'components' => [
        'errorHandler' => [
            'class' => \Wearesho\Yii\Http\ErrorHandler::class,
        ],
        'response' => [
            'class' => \Wearesho\Yii\Http\Response::class,
        ],
    ],
];

```
Add to your DI container:
```php
<?php
\Yii::$container->setSingleton(\yii\db\Connection::class);
```

### TODO
1. Documentation
2. Tests