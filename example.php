<?php

/**
 * Copyright 2025 Alexey Kopytko <alexey@kopytko.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require 'vendor/autoload.php';

use CallableReflection\CallableReflection;

class MyCallable
{
    public function __invoke($a, $b = null) {}
}

class MyClass
{
    public static function staticMethod($x) {}
    public function instanceMethod($x, $y) {}
}

$queue = new SplQueue();

$tests = [
    'Closure with 1 param'            => fn($x) => null,
    'Closure with 2 params'          => function ($a, $b) {},
    'Arrow function with 2 params'   => fn($a, $b) => null,
    'Named function strlen'          => 'strlen',
    'Static method string with 1 param'           => MyClass::class . '::staticMethod',
    'Array [obj, "instanceMethod"] with 2 params'  => [new MyClass(), 'instanceMethod'],
    'Invokable object with 2 params'               => new MyCallable(),
    'First-class callable function'  => strlen(...),
    'First-class static method'      => MyClass::staticMethod(...),
    'First-class instance method'    => (new MyClass())->instanceMethod(...),
    'SplQueue::enqueue method'       => [$queue, 'enqueue'],
    'SplQueue::enqueue method (first class)'       => $queue->enqueue(...),
];

$reflection = new CallableReflection();
foreach ($tests as $label => $cb) {
    try {
        $n = $reflection->reflect($cb)->getNumberOfParameters();
        echo str_pad($label, 60) . " => $n\n";
    } catch (Throwable $e) {
        echo str_pad($label, 60) . " => ERROR: {$e->getMessage()}\n";
    }
}
