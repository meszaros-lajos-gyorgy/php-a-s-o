<?php

class O
{
    // O::isObject(new stdClass()) -> true
    public static function isObject($data): bool
    {
        return is_object($data);
    }

    // O::toPairs(['a' => 1, 'b' => 2]) -> [['a', 1], ['b', 2]]
    // $x = new stdClass(); $x->a = 10; O::toPairs($x) -> [['a', 10]]
    public static function toPairs($data): array
    {
        if (self::isObject($data)) {
            $data = get_object_vars($data);
        }

        $entries = [];

        foreach ($data as $key => $value) {
            $entries[] = [$key, $value];
        }

        return $entries;
    }
}
