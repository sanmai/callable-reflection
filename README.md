# CallableReflection

Get `ReflectionFunctionAbstract` for any callable.

## Installation

```bash
composer require sanmai/callable-reflection
```

## Usage

```php
use CallableReflection\CallableReflection;

$reflection = new CallableReflection();
$params = $reflection->reflect($callable)->getNumberOfParameters();
```

Works with closures, functions, methods, invokable objects, and first-class callables.

If it doesn't work with your callable, [file an issue](https://github.com/sanmai/callable-reflection/issues).

## How is this better than `ReflectionFunction(Closure::fromCallable($callable))`?

*Crickets*

Well... you can dependency inject it I guess?
