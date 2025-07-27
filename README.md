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

I was experimenting with Claude Code, and I was interested in whether it could build something like this entirely from my request. I didn't think very hard whenever it made sense, and it went from a few-sentence request to... Done. It turns out it can.
