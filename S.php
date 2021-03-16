<?php

class S
{
    const CASE_SENSITIVE = false;
    const CASE_INSENSITIVE = true;

    // S::isString("abc") -> true
    public static function isString($data)
    {
        return is_string($data);
    }

    // S::length("aaaa") -> 4
    public static function length($data)
    {
        return mb_strlen($data);
    }

    // S::isEmpty('asdf') -> false
    public static function isEmpty($data)
    {
        return $data === '';
    }

    // S::isNotEmpty('asdf') -> true
    public static function isNotEmpty($data)
    {
        return !self::isEmpty($data);
    }

    // S::toLower("aBcD") -> "abcd"
    public static function toLower($data)
    {
        return mb_strtolower($data);
    }

    // S::toUpper("abCd") -> "ABCD"
    public static function toUpper($data)
    {
        return mb_strtoupper($data);
    }

    // S::includes("ab", "abcd") -> true
    public static function includes($str, $data, $caseSensitivity = self::CASE_SENSITIVE)
    {
        if ($caseSensitivity === self::CASE_INSENSITIVE) {
            return self::includes(self::toLower($str), self::toLower($data), self::CASE_SENSITIVE);
        } else {
            return mb_strpos($data, $str) !== false;
        }
    }

    // S::split(' ', "aab bbc ccd") -> ['aab', 'bbc', 'ccd']
    public static function split($separator, $data)
    {
        // TODO: ez nem biztos, hogy UTF-8 kompatibilis
        // https://www.php.net/manual/en/function.mb-strtolower.php#118378
        return explode($separator, $data);
    }

    // S::equals("aaa", "aaa") -> true
    public static function equals($str, $data, $caseSensitivity = self::CASE_SENSITIVE)
    {
        if ($caseSensitivity === self::CASE_INSENSITIVE) {
            return self::toLower($str) === self::toLower($data);
        } else {
            return $str === $data;
        }
    }

    // S::slice(2, 5, "abcdefgh") -> "cde"
    // S::slice(-3, INF, "abcdefgh") -> "fgh"
    public static function slice($startPos, $endPos, $data)
    {
        if ($startPos < 0) {
            $startPos = self::length($data) + $startPos;
        }
        if ($endPos === INF) {
            $endPos = self::length($data);
        } elseif ($endPos < 0) {
            $endPos = self::length($data) + $endPos;
        }
        return mb_substr($data, $startPos, $endPos - $startPos);
    }

    // S::startsWith("ab", "abcd") -> true
    // https://www.geeksforgeeks.org/php-startswith-and-endswith-functions/
    public static function startsWith($startString, $data, $caseSensitivity = self::CASE_SENSITIVE)
    {
        $len = self::length($startString);
        if ($len === 0) {
            return true;
        }
        return self::equals(self::slice(0, $len, $data), $startString, $caseSensitivity);
    }

    // S::endsWith("ab", "abcd") -> false
    // https://www.geeksforgeeks.org/php-startswith-and-endswith-functions/
    public static function endsWith($endString, $data, $caseSensitivity = self::CASE_SENSITIVE)
    {
        $len = self::length($endString);
        if ($len === 0) {
            return true;
        }
        return self::equals(self::slice(-$len, INF, $data), $endString, $caseSensitivity);
    }

    // S::trim("    123  ") -> "123"
    public static function trim($data)
    {
        return trim($data);
    }
}
