<?php

namespace Shovel;

class S
{
    const CASE_SENSITIVE = false;
    const CASE_INSENSITIVE = true;

    /** S::isString("abc") -> true */
    public static function isString($data): bool
    {
        return is_string($data);
    }

    /** S::length("aaaa") -> 4 */
    public static function length(string $data): int
    {
        return mb_strlen($data);
    }

    /** S::isEmpty('asdf') -> false */
    public static function isEmpty(string $data): bool
    {
        return $data === '';
    }

    /** S::isNotEmpty('asdf') -> true */
    public static function isNotEmpty(string $data): bool
    {
        return !self::isEmpty($data);
    }

    /** S::toLower("aBcD") -> "abcd" */
    public static function toLower(string $data): string
    {
        return mb_strtolower($data);
    }

    /** S::toUpper("abCd") -> "ABCD" */
    public static function toUpper(string $data): string
    {
        return mb_strtoupper($data);
    }

    /** S::includes("ab", "abcd") -> true */
    public static function includes(string $needle, string $haystack, $caseSensitivity = self::CASE_SENSITIVE): bool
    {
        if ($caseSensitivity === self::CASE_INSENSITIVE) {
            return self::includes(self::toLower($needle), self::toLower($haystack), self::CASE_SENSITIVE);
        } else {
            return mb_strpos($haystack, $needle) !== false;
        }
    }

    /** alias for S::includes */
    public static function contains(string $needle, string $haystack, $caseSensitivity = self::CASE_SENSITIVE): bool
    {
        return self::includes($needle, $haystack, $caseSensitivity);
    }

    /** S::split(' ', "aab bbc ccd") -> ['aab', 'bbc', 'ccd'] */
    public static function split(string $separator, string $haystack): array
    {
        return explode($separator, $haystack);
    }

    /** S::equals("aaa", "aaa") -> true */
    public static function equals(string $needle, string $haystack, $caseSensitivity = self::CASE_SENSITIVE): bool
    {
        if ($caseSensitivity === self::CASE_INSENSITIVE) {
            return self::toLower($needle) === self::toLower($haystack);
        } else {
            return $needle === $haystack;
        }
    }

    /**
     * S::slice(2, 5, "abcdefgh") -> "cde"
     * S::slice(-3, PHP_INT_MAX, "abcdefgh") -> "fgh"
     */
    public static function slice(int $startPos, int $endPos, string $data): string
    {
        if ($startPos < 0) {
            $startPos = self::length($data) + $startPos;
        }
        if ($endPos === PHP_INT_MAX) {
            $endPos = self::length($data);
        } elseif ($endPos < 0) {
            $endPos = self::length($data) + $endPos;
        }
        return mb_substr($data, $startPos, $endPos - $startPos);
    }

    /** S::startsWith("ab", "abcd") -> true */
    public static function startsWith(
        string $startString,
        string $data,
        $caseSensitivity = self::CASE_SENSITIVE
    ): bool {
        $len = self::length($startString);
        if ($len === 0) {
            return true;
        }
        return self::equals(self::slice(0, $len, $data), $startString, $caseSensitivity);
    }

    /** S::endsWith("ab", "abcd") -> false */
    public static function endsWith(
        string $endString,
        string $data,
        $caseSensitivity = self::CASE_SENSITIVE
    ): bool {
        $len = self::length($endString);
        if ($len === 0) {
            return true;
        }
        return self::equals(self::slice(-$len, PHP_INT_MAX, $data), $endString, $caseSensitivity);
    }

    /** S::trim("    123  ") -> "123" */
    public static function trim(string $data): string
    {
        return trim($data);
    }

    /** S::replace("a", "b", "appletini") -> "bppletini" */
    public static function replace(string $old, string $new, string $data): string
    {
        return str_replace($old, $new, $data);
    }

    /** S::splitAt(3, "abcdef") -> ["abc", "def"] */
    public static function splitAt(int $index, string $data): array
    {
        $first = self::slice(0, $index, $data);
        $second = self::slice($index, PHP_INT_MAX, $data);
        return [$first, $second];
    }
}
