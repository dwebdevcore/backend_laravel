<?php

namespace App\Http\Decorators;

class OwnershipMethodsDecorator
{
    public function decorate(array $methods): array
    {
        return array_map(function($method, $values) {
            return [
                'method' => $method,
                'values' => $values,
            ];
        }, array_keys($methods), $methods);
    }
}
