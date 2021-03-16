<?php

class O
{
    // O::toPairs(['a' => 1, 'b' => 2]) -> [['a', 1], ['b', 2]]
    public static function toPairs($data)
    {
        $entries = [];

        foreach ($data as $key => $value) {
            $entries[] = [$key, $value];
        }

        return $entries;
    }
}
