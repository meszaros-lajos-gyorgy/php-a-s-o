# Shovel

A PHP library for manipulating Arrays, Strings and Objects - inspired by [ramda.js](https://ramdajs.com/)

## Install

```
composer require mlg/shovel
```

## Concepts

Every method is abide to the following rules ( or at least they should. if they don't, then 1) PRs are welcome, 2) Issues are welcome ):

- **stateless** - each method should get all the necessary info from the parameters and should not rely on any external parameters or state
- **static** - since every method is stateless, there is no need to create class instances
- **pure** - not using anything apart from the passed in parameters
- **immutable** - not going to change any of the parameters, no & references or stuff like that
- **the last parameter should be the input data you are working on...** - like in Lodash FP or Ramda
- **except if the argument list has optional parameters!** - suggestions are welcome on where to place optional parameters
- **not doing any validation on the parameters** - if you are using a method from `A`, then you better be sending it an array. PHP is a loosely typed language and you could spend all day validating input parameters.
- **not casting any of the input parameters** - it's the same as for the validation, you should check the data you pass to the function beforehand
- **does only a single, well defined thing** - small is beautiful, and maintainable - and probably easier to test later on when I'll get the willpower to write tests for this lib
- **null return values on error** - when an error happens and the underlying php function returns false (eg. end or strpos), then it's being normalized to null
- **camelCase naming**

Plain numeric arrays are handled best via the methods in A, while associative arrays and objects are handled via the methods in O.

## API

### Array

- **of** - concatenates every argument into an array as is

  _See also: A::concat()_

  ```php
  $items = A::of(1, 2, [3]); // [1, 2, [3]]
  ```

- **isArray** - checks whether the given parameter is an array (returns true for both numeric and associative)

  This uses the `is_array()` php function

  ```php
  A::isArray([1, 2, 3]); // true
  ```

  ```php
  A::isArray(["a" => 10]); // true
  ```

  ```php
  A::isArray("asdf"); // false
  ```

  ```php
  A::isArray(50); // false
  ```

  ```php
  A::isArray(new stdClass()); // false
  ```

- **isAssoc** - checks whether the given parameter is an associative array. empty arrays are treated as normal arrays and the function will return false for them

  The method is based on this solution: https://stackoverflow.com/a/173479/1806628

  ```php
  A::isAssoc([]); // false
  ```

  ```php
  A::isAssoc([1, 2, 3]); // false;
  ```

  ```php
  A::isAssoc(["x" => 10, "y" => 20]); // true
  ```

- **reduce** -
- **reverse** -
- **reduceRight** -
- **sum** -
- **map** -
- **keys** -
- **values** -
- **equals** -
- **length** -
- **isEmpty** -
- **isNotEmpty** -

- **ensureArray** - wraps parameter into an array if it's not a numeric array

  ```php
  A::ensureArray(123); // [123]
  ```

  ```php
  A::ensureArray([4, 5, 6]); // [4, 5, 6]
  ```

- **append** -
- **prepend** -
- **pluck** -
- **uniq** -
- **uniqByKey** -
- **sortBy** -
- **sortByKey** -
- **unnest** -
- **forEach** -

- **head** - returns the first element of an array, or null, if empty

  ```php
  A::head([1, 2, 3]) // 1
  ```

  ```php
  A::head([]) // null
  ```

- **first** - alias for A::head()

- **last** - returns the last element of an array, or null, if empty

  ```php
  A::last([1, 2, 3, 4, 5]) // 5
  ```

  ```php
  A::last([]) // null
  ```

- **init** - returns a copy of a given array without the last element

  ```php
  A::init([1, 2, 3, 4, 5]) // [1, 2, 3, 4]
  ```

- **tail** - returns a copy of a given array without the first element

  ```php
  A::tail([1, 2, 3, 4, 5]) // [2, 3, 4, 5]
  ```

- **filter** - calls the given function on the elements of an array and returns every value where the function gave truthy value

  ```php
  $numbers = [1, 2, 3, 4, 5, 6];

  function isOdd($n){
    return $n % 2 === 0;
  }

  A::filter('isOdd', $numbers); // [2, 4, 6]
  ```

- **reject** - calls the given function on the elements of an array and removes every value where the function gave truthy value

  ```php
  $numbers = [1, 2, 3, 4, 5, 6];

  function isOdd($n){
    return $n % 2 === 0;
  }

  A::reject('isOdd', $numbers); // [1, 3, 5]
  ```

- **find** - calls the given function on the elements of an array and returns the value for the first match. if there's no match, it will return `null`

  ```php
  $data = [
    ["a" => 8],
    ["a" => 10],
    ["a" => 12]
  ];

  $result = A::find(fn($x) => $x["a"] > 3, $data);

  // $result = ["a" => 8]
  ```

  ```php
  $data = [
    ["a" => 8],
    ["a" => 10],
    ["a" => 12]
  ];

  $result = A::find(fn($x) => $x["a"] === -4, $data);

  // $result = null
  ```

- **findLast** - calls the given function on the elements of an array and returns the value for the last match. if there's no match, it will return `null`

  ```php
  $data = [
    ["a" => 8],
    ["a" => 10],
    ["a" => 12]
  ];

  $result = A::findLast(fn($x) => $x["a"] > 3, $data);

  // $result = ["a" => 12]
  ```

  ```php
  $data = [
    ["a" => 8],
    ["a" => 10],
    ["a" => 12]
  ];

  $result = A::findLast(fn($x) => $x["a"] === -4, $data);

  // $result = null
  ```

- **findIndex** - calls the given function on the elements of an array and returns the key for the first match. if there's no match it will return `null`

  ```php
  $data = [
    ["a" => 8],
    ["a" => 10],
    ["a" => 12]
  ];

  $result = A::findIndex(fn($x) => $x["a"] === 10, $data);

  // $result = 1
  ```

  ```php
  $data = [
    ["a" => 8],
    ["a" => 10],
    ["a" => 12]
  ];

  $result = A::findIndex(fn($x) => $x["a"] === -4, $data);

  // $result = null
  ```

- **findLastIndex** - calls the given function on the elements of an array and returns the key for the last match. if there's no match it will return `null`

  ```php
  $data = [
    ["a" => 8],
    ["a" => 10],
    ["a" => 12]
  ];

  $result = A::findLastIndex(fn($x) => $x["a"] > 3, $data);

  // $result = 2
  ```

  ```php
  $data = [
    ["a" => 8],
    ["a" => 10],
    ["a" => 12]
  ];

  $result = A::findLastIndex(fn($x) => $x["a"] > 500, $data);

  // $result = null
  ```

- **any** - calls the given predicate function on the elements in the given array and returns true if for at least one of them the predicate returns true

  ```php
  $data = [2, 3, 5, 6, 7, 9, 10];

  $result = A::any(fn($x) => $x % 5 === 0, $data);

  // $result = true
  ```

- **none** -
- **all** -

- **includes** -
- **contains** -
- **slice** -
- **join** -

- **pickRandom** - selects a random item from the given array

- **concat** - concatenates every argument into an array. if any of the arguments are numeric arrays, then those will get unnested

  _See also: A::of()_

  ```php
  A::concat([1, 2], 3, [4, 5]); // [1, 2, 3, 4, 5]
  ```

- **zipObj** -

### String

> Most string operations come with an optional 3rd parameter called $caseSensitivity,
> which can be either `S::CASE_SENSITIVE` (default) or `S::CASE_INSENSITIVE`.

> All string operations are multibyte safe!

- **isString** - checks whether given argument is a string

  ```php
  S::isString('hello'); // true
  ```

  ```php
  S::isString(['hello']); // false
  ```

  ```php
  S::isString(304.2); // false
  ```

- **length** - counts the number of characters in the given parameter

  ```php
  S::length('őz'); // 2 -- strlen('őz') returns 3
  ```

- **isEmpty** - checks whether the given string has no characters

  ```php
  S::isEmpty(''); // true
  ```

  ```php
  S::isEmpty('caterpillar'); // false
  ```

- **isNotEmpty** - checks whether the given string contains any characters

  ```php
  S::isNotEmpty(''); // false
  ```

  ```php
  S::isNotEmpty('caterpillar'); // true
  ```

- **toLower** - converts every character in a string to lowercase

  ```php
  S::toLower('AsDf JkLÉ'); // "asdf jklé"
  ```

- **toUpper** - converts every character in a string to uppercase

  ```php
  S::toUpper('AsDf JkLÉ'); // "ASDF JKLÉ"
  ```

- **includes** - checks, if the string given as the 1st parameter is a substring of the 2nd parameter string

  ```php
  S::includes('erf', 'butterfly'); // true
  ```

  ```php
  S::includes('ERF', 'butterfly', S::CASE_INSENSITIVE); // true
  ```

  ```php
  S::includes('ERF', 'butterfly', S::CASE_SENSITIVE); // false
  ```

  ```php
  S::includes('', 'butterfly'); // false -- edge case
  ```

- **contains** - alias for S::includes()

- **split** - splits a string into multiple parts at points matching another string

  ```php
  S::split("/", "foo/bar/baz") // ["foo", "bar", "baz"]
  ```

- **splitAt** - splits a string into 2 at a given position

  ```php
  S::splitAt(3, "abcdef") // ["abc", "def"]
  ```

- **equals** - compares two strings together to see if they match

  ```php
  S::equals('asdf', 'asdf'); // true
  ```

  ```php
  S::equals('asdf', 'ASDF', S::CASE_INSENSITIVE); // true
  ```

  ```php
  S::equals('asdf', 'ASDF', S::CASE_SENSITIVE); // false
  ```

- **slice** - copies a substring between starting(inclusive) and ending(exclusive) positions

  ```php
  S::slice(2, 5, "abcdefgh"); // "cde"
  ```

  ```php
  S::slice(-3, PHP_INT_MAX, "abcdefgh") // "fgh"
  ```

- **startsWith** - checks if the second parameter starts with the first

  ```php
  S::startsWith("inf", "infinity"); // true
  ```

  ```php
  S::startsWith("inf", "iNFinItY", S::CASE_INSENSITIVE); // true
  ```

  ```php
  S::startsWith("inf", "iNFinItY", S::CASE_SENSITIVE); // false
  ```

- **endsWith** - checks if the second parameter ends with the first

  ```php
  S::endsWith("ed", "throwed"); // true
  ```

  ```php
  S::endsWith("ed", "tHRoWeD", S::CASE_SENSITIVE); // false
  ```

  ```php
  S::endsWith("ed", "tHRoWeD", S::CASE_INSENSITIVE); // true
  ```

- **trim** - removes leading and trailing whitespaces from a string

  ```php
  S::trim("  asd f     "); // "asd f"
  ```

- **replace** - replaces substring with another

  ```php
  S::replace("a", "e", "alabama"); // "elebeme"
  ```

### Object

- **isObject** - check whether the passed in argument is an object

  ```php
  $point = new stdClass();
  $point->x = 10;
  $point->y = 20;
  O::isObject($point); // true
  ```

  ```php
  O::isObject("asdf"); // false
  ```

- **toPairs** - gets all keys and values of an array or object and returns it as array of key-value pairs

  ```php
  $point = new stdClass();
  $point->x = 10;
  $point->y = 20;
  O::toPairs($point); // [["x", 10], ["y", 20]]
  ```

  ```php
  $user = [
    "firstName" => "John",
    "lastName" => "Doe"
  ];
  O::toPairs($user); // [["firstName", "John"], ["lastName", "Doe"]]
  ```

  ```php
  $temperatures = [75, 44, 36];
  O::toPairs($temperatures); // [[0, 75], [1, 44], [2, 36]]
  ```

- **pick** -

- **assoc** - assigns value to an object via a given key. already existing keys will get overwritten

  ```php
  $point2d = new stdClass();
  $point2d->x = 10;
  $point2d->y = 20;

  $point3d = O::assoc("z", 30, $point2d); // {"x": 10, "y": 20, "z": 30}
  ```

- **dissoc** - removes a key from an object

  ```php
  $point3d = new stdClass();
  $point3d->x = 10;
  $point3d->y = 20;
  $point3d->z = 30;

  $point2d = O::dissoc("z", 30, $point3d); // {"x": 10, "y": 20}
  ```

- **has** - checks presence of a key inside an object and an associative array

  uses `array_key_exists()` internally

  ```php
  $data = new stdClass();
  $data->x = 10;

  O::has('x', $data); // true
  O::has('y', $data); // false
  ```

- **keys** -
- **values** -

### Functions

- **complement** -

## Future plans

I keep adding methods as I come across the need for them, so if you're missing a method you'd use, then 1) PRs are welcome, 2) Issues are welcome.

## If the methods are all static and stateless, then why not just write simple functions?

There are multiple functions, which have the same parameter signature, but operate with different parameter types.
To avoid having to type check the parameters at every function call I've chose to namespace the functions based on the types they work on into static methods.

For example take a look at includes for both Arrays and Strings. Their implementation is relative simple, because their types are expected to be arrays and strings respectively (type hinting will come soon).

```php
class A {
  public static function includes($value, $data)
  {
    return in_array($value, $data, true);
  }
}

class S {
  public static function includes($value, $data)
  {
    return mb_strpos($data, $str) !== false;
  }
}
```

If I were to have a single, combined `includes` function, then I would have to do type checking every time and it would make the code unnecessarily noisy.

Plus, sometimes the function name I would like to use is already taken by PHP, like in the case of [S::split](https://www.php.net/manual/en/function.split.php)

## Credits

https://www.geeksforgeeks.org/php-startswith-and-endswith-functions/ - used for S::startsWith() and S::endsWith()

https://stackoverflow.com/a/173479/1806628 - used for A::isAssoc()

https://www.php.net/manual/en/function.array-unique.php#116302 - used for A::uniqByKey()
