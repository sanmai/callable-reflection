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

namespace CallableReflection\Tests;

use CallableReflection\CallableReflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SplQueue;

#[CoversClass(CallableReflection::class)]
class CallableReflectionTest extends TestCase
{
    private CallableReflection $callableReflection;

    protected function setUp(): void
    {
        $this->callableReflection = new CallableReflection();
    }

    public function testClosureWithOneParam(): void
    {
        $callable = fn($x) => null;
        $reflection = $this->callableReflection->reflect($callable);

        $this->assertSame(1, $reflection->getNumberOfParameters());
    }

    public function testClosureWithTwoParams(): void
    {
        $callable = function ($a, $b) {};
        $reflection = $this->callableReflection->reflect($callable);

        $this->assertSame(2, $reflection->getNumberOfParameters());
    }

    public function testArrowFunctionWithTwoParams(): void
    {
        $callable = fn($a, $b) => null;
        $reflection = $this->callableReflection->reflect($callable);

        $this->assertSame(2, $reflection->getNumberOfParameters());
    }

    public function testNamedFunctionString(): void
    {
        $reflection = $this->callableReflection->reflect('strlen');

        $this->assertSame(1, $reflection->getNumberOfParameters());
        $this->assertSame('strlen', $reflection->getName());
    }

    public function testStaticMethodString(): void
    {
        $callable = TestHelper::class . '::staticMethod';
        $reflection = $this->callableReflection->reflect($callable);

        $this->assertSame(1, $reflection->getNumberOfParameters());
        $this->assertSame('staticMethod', $reflection->getName());
    }

    public function testArrayWithObjectAndMethod(): void
    {
        $helper = new TestHelper();
        $callable = [$helper, 'instanceMethod'];
        $reflection = $this->callableReflection->reflect($callable);

        $this->assertSame(2, $reflection->getNumberOfParameters());
        $this->assertSame('instanceMethod', $reflection->getName());
    }

    public function testInvokableObject(): void
    {
        $callable = new InvokableTestClass();
        $reflection = $this->callableReflection->reflect($callable);

        $this->assertSame(2, $reflection->getNumberOfParameters());
        $this->assertSame('__invoke', $reflection->getName());
    }

    public function testFirstClassCallableFunction(): void
    {
        $callable = strlen(...);
        $reflection = $this->callableReflection->reflect($callable);

        $this->assertSame(1, $reflection->getNumberOfParameters());
    }

    public function testFirstClassStaticMethod(): void
    {
        $callable = TestHelper::staticMethod(...);
        $reflection = $this->callableReflection->reflect($callable);

        $this->assertSame(1, $reflection->getNumberOfParameters());
    }

    public function testFirstClassInstanceMethod(): void
    {
        $helper = new TestHelper();
        $callable = $helper->instanceMethod(...);
        $reflection = $this->callableReflection->reflect($callable);

        $this->assertSame(2, $reflection->getNumberOfParameters());
    }

    public function testSplQueueMethod(): void
    {
        $queue = new SplQueue();
        $callable = [$queue, 'enqueue'];
        $reflection = $this->callableReflection->reflect($callable);

        $this->assertSame(1, $reflection->getNumberOfParameters());
        $this->assertSame('enqueue', $reflection->getName());
    }
}

class TestHelper
{
    public static function staticMethod($x): void {}

    public function instanceMethod($x, $y): void {}
}

class InvokableTestClass
{
    public function __invoke($a, $b = null): void {}
}
