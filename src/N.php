<?php

namespace Shovel;

class N {
    // N::isIntLike('12')      // true
    // N::isIntLike(3.14)      // true
    // N::isIntLike(7)         // true
    // N::isIntLike('23.5')    // false
    // N::isIntLike([1, 2, 3]) // false
    public static function isIntLike($n): bool
    {
        return is_int($n) || is_float($n) || (is_string($n) && intval($n) . '' === $n);
    }

    public static function toInt($n): ?int
    {
        return $n === null ? null : intval($n);
    }
}
