<?php

namespace Shovel;

class F
{
    public static function complement(callable $fn): callable
    {
        return fn(...$args) => !$fn(...$args);
    }
}
