<?php

namespace Shovel;

use stdClass;

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

    // $coord = {x:10, y:20, z:30}; O::pick(['x', 'y'], $coord) -> {x:10, y:20}
    public static function pick(array $keys, object $data): object
    {
        return A::reduce(
            function ($result, $key) use ($data) {
                if (isset($data->{$key})) {
                    $result->{$key} = $data->{$key};
                }
                return $result;
            },
            new stdClass(),
            $keys
        );
    }

    // O::assoc('foo', 'bar', {}) -> {foo: 'bar'}
    public static function assoc(string $key, $value, object $data): object {
        $data->{$key} = $value;
        return $data;
    }

    // O::dissoc('foo', {foo: 'bar', fizz: 'buzz'}) -> {fizz: 'buzz'}
    public static function dissoc(string $key, object $data): object {
        unset($data->{$key});
        return $data;
    }

    // O::has('x', {x:10, y:20}) -> true
    public static function has(string $key, $data): bool {
        return array_key_exists($key, $data);
    }

    public static function keys($data): array {
        if (self::isObject($data)) {
            return A::keys(get_object_vars($data));
        }
        if (A::isArray($data) && A::isAssoc($data)) {
            return A::keys($data);
        }
        return [];
    }

    public static function values($data): array {
        if (self::isObject($data)) {
            return A::values(get_object_vars($data));
        }
        if (A::isArray($data) && A::isAssoc($data)) {
            return A::values($data);
        }
        return [];
    }
}
