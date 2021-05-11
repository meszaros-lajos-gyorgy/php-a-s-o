<?php

namespace Shovel;

use stdClass;

class A
{
    // A::of(1, 2, 3) -> [1, 2, 3]
    public static function of(...$args): array
    {
        return $args;
    }

    // A::isArray([1, 2, 3]) -> true
    public static function isArray($data): bool
    {
        return is_array($data);
    }

    // A::isAssoc(['a' => 12]) -> true
    public static function isAssoc(array $data): bool
    {
        if (self::isEmpty($data)) {
            return false;
        }

        return !self::equals(range(0, self::length($data) - 1), self::keys($data));
    }

    // A::reduce($fn, $init, [1, 2, 3]) -> $fn(3, $fn(2, $fn(1, $init)))
    public static function reduce(callable $fn, $initialValue, array $data)
    {
        return array_reduce($data, $fn, $initialValue);
    }

    // A::reverse([1, 2, 3]) -> [3, 2, 1]
    public static function reverse(array $data): array
    {
        return array_reverse($data);
    }

    // A::reduceRight($fn, $init, [1, 2, 3]) -> $fn(1, $fn(2, $fn(3, $init)))
    public static function reduceRight(callable $fn, $initialValue, array $data)
    {
        return self::reduce($fn, $initialValue, self::reverse($data));
    }

    // A::sum([1, 2, 3]) -> 6
    public static function sum(array $data)
    {
        //: int|float
        return array_sum($data);
    }

    // A::map($fn, [1, 2, 3]) -> [$fn(1), $fn(2), $fn(3)]
    public static function map(callable $fn, array $data): array
    {
        return array_map($fn, $data);
    }

    // A::keys([3, 6, 9]) -> [0, 1, 2]
    public static function keys(array $data): array
    {
        return array_keys($data);
    }

    // A::values([3, 6, 7]) -> [3, 6, 7]
    public static function values(array $data): array
    {
        return array_values($data);
    }

    // A::equals([1, 2, 3], [1, 2, 3]) -> true
    public static function equals(array $arr, array $data): bool
    {
        return $data === $arr;
    }

    // A::length([1, 2, 3]) -> 3
    public static function length(array $data): int
    {
        return count($data);
    }

    // A::isEmpty([1, 2, 3]) -> false
    public static function isEmpty(array $data): bool
    {
        return self::length($data) === 0;
    }

    // A::isNotEmpty([1, 2, 3]) -> true
    public static function isNotEmpty(array $data): bool
    {
        return !self::isEmpty($data);
    }

    // A::ensureArray(['a' => 10]) -> [['a' => 10]]
    public static function ensureArray($data): array
    {
        if (self::isArray($data) && !self::isAssoc($data)) {
            return $data;
        } else {
            return self::of($data);
        }
    }

    // A::append([4, 5, 6], [1, 2]) -> [1, 2, 4, 5, 6]
    public static function append(array $arr, array $data): array
    {
        return array_merge($data, self::ensureArray($arr));
    }

    // A::prepend([4, 5, 6], [1, 2]) -> [4, 5, 6, 1, 2]
    public static function prepend(array $arr, array $data): array
    {
        return array_merge(self::ensureArray($arr), $data);
    }

    // A::pluck('color', [['color' => 'red', ...], ['color' => 'green', ...]]) -> ['red', 'green']
    public static function pluck($key, array $data): array
    {
        return array_column($data, $key);
    }

    // A::uniq([1, 2, 2, 3, 3, 3]) -> [1, 2, 3]
    public static function uniq(array $data): array
    {
        return array_values(array_unique($data, SORT_REGULAR));
    }

    // A::uniqByKey('color', [['color' => 'red', ...], ['color' => 'red', ...]]) -> [['color => 'red', ...]]
    public static function uniqByKey($key, array $data): array
    {
        $i = 0;
        $tempArray = [];
        $keyArray = [];

        foreach ($data as $val) {
            if (!in_array($val[$key], $keyArray)) {
                $keyArray[$i] = $val[$key];
                $tempArray[$i] = $val;
            }
            $i++;
        }
        return $tempArray;
    }

    // A::sortBy(function($a, $b){ return $b - $a; }, [3, 5, 1]) -> [1, 3, 5]
    public static function sortBy(callable $fn, array $data): array
    {
        usort($data, $fn);
        return $data;
    }

    // A::sortByKey('age', 'number', 'asc', [['age' => 10, ...], ['age' => 5, ...]]) -> [['age' => 5, ...], ['age' => 10, ...]]
    public static function sortByKey(
        $key,
        string $type,
        string $direction,
        array $data
    ): array {
        switch ($type) {
            case 'string':
                if ($direction === 'desc') {
                    $sorter = function ($a, $b) use ($key) {
                        return strcasecmp($b[$key], $a[$key]);
                    };
                } else {
                    $sorter = function ($a, $b) use ($key) {
                        return strcasecmp($a[$key], $b[$key]);
                    };
                }
                break;
            case 'date':
                if ($direction === 'desc') {
                    $sorter = function ($a, $b) use ($key) {
                        return strtotime($b[$key]) - strtotime($a[$key]);
                    };
                } else {
                    $sorter = function ($a, $b) use ($key) {
                        return strtotime($a[$key]) - strtotime($b[$key]);
                    };
                }
                break;
            default:
                if ($direction === 'desc') {
                    $sorter = function ($a, $b) use ($key) {
                        return $b[$key] - $a[$key];
                    };
                } else {
                    $sorter = function ($a, $b) use ($key) {
                        return $a[$key] - $b[$key];
                    };
                }
                break;
        }

        return self::sortBy($sorter, $data);
    }

    // A::unnest([[1, 2, 3], [3, 4, [5]]]) -> [1, 2, 3, 3, 4, [5]]
    public static function unnest($data, int $levels = 1): array
    {
        // TODO: implement this to work with any $levels
        // it might be enought just to do a recursion until --$levels === 0
        return self::reduce(
            function ($acc, $x) {
                return self::append($x, $acc);
            },
            [],
            $data
        );
    }

    // A::forEach($fn, [1, 2, 3]) -> $fn(1) -> $fn(2) -> $fn(3) -> undefined
    public static function forEach(callable $fn, array $data): void
    {
        A::map($fn, $data);
    }

    // A::last(['aa', 'bb', 'cc']) -> 'cc'
    // A::last([]) -> null
    public static function last(array $data)
    {
        if (self::isEmpty($data)) {
            return null;
        }

        return end($data);
    }

    // A::head(['aa', 'bb', 'cc']) -> 'aa'
    // A::head([]) -> null
    public static function head(array $data)
    {
        if (self::isEmpty($data)) {
            return null;
        }

        return reset($data);
    }

    // alias for A::head()
    public static function first(array $data)
    {
        return self::head($data);
    }

    // A::tail([1, 2, 3]) -> [2, 3]
    public static function tail(array $data): array {
        return self::slice(1, PHP_INT_MAX, $data);
    }

    // A::init([1, 2, 3]) -> [1, 2]
    public static function init(array $data): array {
        return self::slice(0, -1, $data);
    }

    // A::filter(x => x % 2 == 0, [1, 2, 3, 4, 5]) -> [2, 4]
    public static function filter(callable $fn, array $data): array
    {
        return self::values(array_filter($data, $fn));
    }

    public static function reject(callable $fn, array $data): array
    {
        return self::filter(fn($value) => !$fn($value), $data);
    }

    // A::find(x => x.a > 3, [['a' => 8], ['a' => 10]]) -> ['a' => 8]
    public static function find(callable $fn, array $data)
    {
        return self::head(self::filter($fn, $data));
    }

    // A::findLast(x => x.a > 3, [['a' => 8], ['a' => 10]]) -> ['a' => 10]
    public static function findLast(callable $fn, array $data) {
        return self::find($fn, self::reverse($data));
    }

    // A::findIndex(x => x.a === 10, [['a' => 8], ['a' => 10]]) -> 1
    public static function findIndex(callable $fn, array $data): ?int {
        if (self::isEmpty($data)) {
            return null;
        }

        foreach ($data as $key => $value) {
            if ($fn($value)) {
                return $key;
            }
        }

        return null;
    }

    // A::findLastIndex(x => x.a > 3, [['a' => 8], ['a' => 10]]) -> 1
    public static function findLastIndex(callable $fn, array $data): ?int
    {
        return self::findIndex($fn, self::reverse($data));
    }

    // A::any(x => x.a === 10, [['a' => 8], ['a' => 10]]) -> true
    public static function any(callable $fn, array $data): bool
    {
        return self::find($fn, $data) !== null;
    }

    // A::includes('baz', ['foo', 'bar', 'baz']) -> true
    public static function includes($value, array $data): bool
    {
        return in_array($value, $data, true);
    }

    // A::slice(2, 4, [1, 2, 3, 4, 5]) -> [3, 4, 5]
    public static function slice(int $fromIndex, int $toIndex, array $data): array
    {
        return array_slice($data, $fromIndex, $toIndex);
    }

    // A::join('-', ['a', 'b', 'c']) -> 'a-b-c'
    public static function join(string $separator, array $data): string
    {
        return implode($separator, $data);
    }

    // A::pickRandom([1, 2, 3, 4, 5]) -> 3
    public static function pickRandom(array $data)
    {
        return $data[rand(0, self::length($data) - 1)];
    }

    // A::concat([1, 2], 3, [4, 5]) -> [1, 2, 3, 4, 5]
    public static function concat(...$args) {
        return self::unnest(self::map(fn($arg) => self::ensureArray($arg),$args));
    }

    // A::zipObj(['a', 'b'], [1, 2]) -> {a:1, b:2}
    public static function zipObj(array $keys, array $values): object {
        $_keys = self::uniq(self::concat(self::keys($keys), self::keys($values)));
        $_keys = self::filter(fn($key) => array_key_exists($key, $keys) && array_key_exists($key, $values), $_keys);
        return self::reduce(fn($result, $key) => O::assoc($keys[$key], $values[$key], $result), new stdClass(), $_keys);
    }
}
