<?php

class A
{
    // A::of(1, 2, 3) -> [1, 2, 3]
    public static function of()
    {
        return func_get_args();
    }

    // A::isArray([1, 2, 3]) -> true
    public static function isArray($data)
    {
        return is_array($data);
    }

    // A::isAssoc(['a' => 12]) -> true
    // https://stackoverflow.com/a/173479/1806628
    public static function isAssoc($data)
    {
        if (self::isEmpty($data)) {
            return false;
        }

        return !self::equals(range(0, self::length($data) - 1), self::keys($data));
    }

    // A::reduce($fn, $init, [1, 2, 3]) -> $fn(3, $fn(2, $fn(1, $init)))
    public static function reduce($fn, $initialValue, $data)
    {
        return array_reduce($data, $fn, $initialValue);
    }

    // A::reverse([1, 2, 3]) -> [3, 2, 1]
    public static function reverse($data)
    {
        return array_reverse($data);
    }

    // A::reduceRight($fn, $init, [1, 2, 3]) -> $fn(1, $fn(2, $fn(3, $init)))
    public static function reduceRight($fn, $initialValue, $data)
    {
        return self::reduce($fn, $initialValue, self::reverse($data));
    }

    // A::sum([1, 2, 3]) -> 6
    public static function sum($data)
    {
        return array_sum($data);
    }

    // A::map($fn, [1, 2, 3]) -> [$fn(1), $fn(2), $fn(3)]
    public static function map($fn, $data)
    {
        return array_map($fn, $data);
    }

    // A::keys([3, 6, 9]) -> [0, 1, 2]
    public static function keys($data)
    {
        return array_keys($data);
    }

    // A::values([3, 6, 7]) -> [3, 6, 7]
    public static function values($data)
    {
        return array_values($data);
    }

    // A::equals([1, 2, 3], [1, 2, 3]) -> true
    public static function equals($arr, $data)
    {
        return $data === $arr;
    }

    // A::length([1, 2, 3]) -> 3
    public static function length($data)
    {
        return count($data);
    }

    // A::isEmpty([1, 2, 3]) -> false
    public static function isEmpty($data)
    {
        return self::length($data) === 0;
    }

    // A::isNotEmpty([1, 2, 3]) -> true
    public static function isNotEmpty($data)
    {
        return !self::isEmpty($data);
    }

    // A::ensureArray(['a' => 10]) -> [['a' => 10]]
    public static function ensureArray($data)
    {
        if (self::isArray($data) && !self::isAssoc($data)) {
            return $data;
        } else {
            return self::of($data);
        }
    }

    // A::append([4, 5, 6], [1, 2]) -> [1, 2, 4, 5, 6]
    public static function append($arr, $data)
    {
        return array_merge($data, self::ensureArray($arr));
    }

    // A::prepend([4, 5, 6], [1, 2]) -> [4, 5, 6, 1, 2]
    public static function prepend($arr, $data)
    {
        return array_merge(self::ensureArray($arr), $data);
    }

    // A::pluck('color', [['color' => 'red', ...], ['color' => 'green', ...]]) -> ['red', 'green']
    public static function pluck($key, $data)
    {
        return array_column($data, $key);
    }

    // A::uniq([1, 2, 2, 3, 3, 3]) -> [1, 2, 3]
    public static function uniq($data)
    {
        return array_values(array_unique($data, SORT_REGULAR));
    }

    // A::uniqByKey('color', [['color' => 'red', ...], ['color' => 'red', ...]]) -> [['color => 'red', ...]]
    // https://www.php.net/manual/en/function.array-unique.php#116302
    public static function uniqByKey($key, $data)
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
    public static function sortBy($fn, $data)
    {
        usort($data, $fn);
        return $data;
    }

    // A::sortByKey('age', 'number', 'asc', [['age' => 10, ...], ['age' => 5, ...]]) -> [['age' => 5, ...], ['age' => 10, ...]]
    public static function sortByKey($key, $type = 'date', $direction = 'asc', $data)
    {
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
    public static function unnest($data, $levels = 1)
    {
        // TODO: implementálni azt, hogy több level-re legyen jó
        // talán elég csak annyi, hogy rekurzívan meghívjuk az unnest-et, amíg $levels-- > 0
        return self::reduce(
            function ($acc, $x) {
                return self::append($x, $acc);
            },
            [],
            $data
        );
    }

    // A::forEach($fn, [1, 2, 3]) -> $fn(1) -> $fn(2) -> $fn(3) -> undefined
    public static function forEach($fn, $data)
    {
        A::map($fn, $data);
    }

    // A::last(['aa', 'bb', 'cc']) -> 'cc'
    public static function last($data)
    {
        return end($data);
    }

    // A::head(['aa', 'bb', 'cc']) -> 'aa'
    public static function head($data)
    {
        return reset($data);
    }

    // A::first(['aa', 'bb', 'cc']) -> 'aa'
    // a head() alias-a
    public static function first($data)
    {
        return self::head($data);
    }

    // A::filter(x => x % 2 == 0, [1, 2, 3, 4, 5]) -> [2, 4]
    public static function filter($fn, $data)
    {
        return A::values(array_filter($data, $fn));
    }

    // A::find(x => x.a === 10, [['a' => 8], ['a' => 10]]) -> ['a' => 10]
    public static function find($fn, $data)
    {
        $results = self::filter($fn, $data);
        if (self::isEmpty($results)) {
            return null;
        } else {
            return self::first($results);
        }
    }

    // A::find(x => x.a === 10, [['a' => 8], ['a' => 10]]) -> true
    public static function any($fn, $data)
    {
        return self::find($fn, $data) !== null;
    }

    // A::includes('baz', ['foo', 'bar', 'baz']) -> true
    public static function includes($value, $data)
    {
        return in_array($value, $data, true);
    }

    // A::slice(2, 4, [1, 2, 3, 4, 5]) -> [3, 4, 5]
    public static function slice($fromIndex, $toIndex, $data)
    {
        // TODO: negatív számokkal is menjen
        return array_slice($data, $fromIndex, $toIndex);
    }

    // A::join('-', ['a', 'b', 'c']) -> 'a-b-c'
    public static function join($separator, $data)
    {
        return implode($separator, $data);
    }

    // A::pickRandom([1, 2, 3, 4, 5]) -> 3
    public static function pickRandom($data)
    {
        return $data[rand(0, self::length($data) - 1)];
    }
}
