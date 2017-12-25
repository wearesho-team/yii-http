# Yii2 Http

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
 * 
 * Array
 * (
 *   [bar] => foo
 * )
 */

```

### GetParamsBehavior [[Example]](./tests/Behaviors/GetParamsBehaviorTest.php)
Fills Panel attributes from \yii\web\Request::get().

## Installation
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

## TODO
1. Documentation
2. Tests

## LICENSE
Unlicensed