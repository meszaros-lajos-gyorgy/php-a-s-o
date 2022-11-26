<?php

namespace Shovel;

class N
{
    public static function toInt($n): ?int
    {
        return $n === null ? null : intval($n);
    }
}
