<?php

namespace Shovel;

class F
{
    public static function complement(callable $fn): callable
    {
        return function (...$args) use ($fn) {
            return !$fn(...$args);
        };
    }
}
