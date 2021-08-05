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
    public static function pick(array $keys, $data): object
    {
        return A::reduce(
            function ($result, $key) use ($data) {
                if (self::has($key, $data)) {
                    $result->{$key} = self::prop($key, $data);
                }
                return $result;
            },
            new stdClass(),
            $keys
        );
    }

    // O::assoc('foo', 'bar', {}) -> {foo: 'bar'}
    public static function assoc(string $key, $value, $data)
    {
        if (self::isObject($data)) {
            $data->{$key} = $value;
        }

        if (A::isArray($data) && A::isAssoc($data)) {
            $data[$key] = $value;
        }

        return $data;
    }

    // O::assocPath(['foo', 'bar'], 12, {}) -> {foo: {bar: 12}}
    function assocPath($path, $value, $data) {
        $tmp = &$data;
        
        for ($i = 0; $i < count($path); $i++) {
            $tmp = &$tmp[$path[$i]];
        }
        
        $tmp = $value;
        
        return $data;
    }

    // O::dissoc('foo', {foo: 'bar', fizz: 'buzz'}) -> {fizz: 'buzz'}
    public static function dissoc(string $key, $data)
    {
        if (self::isObject($data)) {
            unset($data->{$key});
        }

        if (A::isArray($data) && A::isAssoc($data)) {
            unset($data[$key]);
        }

        return $data;
    }

    // O::has('x', {x:10, y:20}) -> true
    public static function has(string $key, $data): bool {
        if (self::isObject($data)) {
            return property_exists($data, $key);
        }

        if (A::isArray($data) && A::isAssoc($data)) {
            return array_key_exists($key, $data);
        }

        return false;
    }

    // O::keys(['a' => 1, 'b' => 2]) -> ['a', 'b']
    public static function keys($data): array {
        if (self::isObject($data)) {
            return A::keys(get_object_vars($data));
        }
        if (A::isArray($data) && A::isAssoc($data)) {
            return A::keys($data);
        }
        return [];
    }

    // O::values(['a' => 1, 'b' => 2]) -> [1, 2]
    public static function values($data): array {
        if (self::isObject($data)) {
            return A::values(get_object_vars($data));
        }
        if (A::isArray($data) && A::isAssoc($data)) {
            return A::values($data);
        }
        return [];
    }

    // O::prop('x', ['x' => 10]) -> 10
    public static function prop($key, $data) {
        if (self::isObject($data)) {
            return $data->{$key} ?? null;
        }
        if (A::isArray($data) && A::isAssoc($data)) {
            return $data[$key] ?? null;
        }
        return null;
    }
}
