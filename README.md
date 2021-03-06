# Yii2 Http
[![Latest Stable Version](https://poser.pugx.org/wearesho-team/yii-http/v/stable.png)](https://packagist.org/packages/wearesho-team/yii-http)
[![Total Downloads](https://poser.pugx.org/wearesho-team/yii-http/downloads.png)](https://packagist.org/packages/wearesho-team/yii-http)
[![Build Status](https://travis-ci.org/wearesho-team/yii-http.svg?branch=master)](https://travis-ci.org/wearesho-team/yii-http)
[![codecov](https://codecov.io/gh/wearesho-team/yii-http/branch/master/graph/badge.svg)](https://codecov.io/gh/wearesho-team/yii-http)

Alternative work with HTTP

## Contents

### View
Implement your view
```php
<?php

namespace App\Views;

use Wearesho\Yii\Http\View;

class EntityView extends View {
    /** @var string  */
    protected $foo;
    
    /** @var \SomeClass  */
    protected $dependency;
    
    public function __construct(string $foo, \SomeClass $dependency) {
        $this->foo = $foo;
        $this->dependency = $dependency;
    }
    
    protected function renderInstantiated(): array {
        return [
            'bar' => $this->foo,
        ];
    }
}

```
then use it
```php
<?php

use App\Views\EntityView;

$argument = 'foo';
$output = EntityView::render($argument);

print_r($output);

/**
 * Will output: 
 * Array
  (
      [bar] => 1
  )
 */
// or if you have multiple data
$arguments = [
    '1',
    '2',
];
$output = EntityView::multiple($arguments);

/**
 * Will output
 * Array
   (
       [0] => Array
           (
               [bar] => 1
           )
   
       [1] => Array
           (
               [bar] => 2
           )
   
   )
 */
```
### GetParamsBehavior [[Example]](./tests/Behaviors/GetParamsBehaviorTest.php)
Fills Panel attributes from \yii\web\Request::get().

## Installation
```bash
composer require wearesho-team/yii-http
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

## TODO
1. Documentation
2. Tests

## LICENSE
MIT
