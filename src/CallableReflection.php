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

namespace CallableReflection;

use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;

class CallableReflection
{
    public function reflect(callable $callable): ReflectionFunctionAbstract
    {
        if (is_string($callable) && str_contains($callable, '::')) {
            $callable = explode('::', $callable);
        }

        return match (true) {
            is_array($callable)  => new ReflectionMethod(...$callable),
            is_object($callable) => new ReflectionMethod($callable, '__invoke'),
            default => new ReflectionFunction($callable),
        };
    }
}
