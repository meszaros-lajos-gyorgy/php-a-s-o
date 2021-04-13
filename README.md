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

## API

### Array

- **of** - concatenates every argument into an array as is

```php
$items = A::of(1, 2, [3]); // [1, 2, [3]]
```

- **isArray** - checks whether the given parameter is an array (returns true for both numeric and associative)

```php
A::isArray([1, 2, 3]); // true
A::isArray(['a' => 10]); // true
A::isArray('asdf'); // false
A::isArray(50); // false
A::isArray(new stdClass()); // false
```

- **isAssoc** -
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
- **ensureArray** - wraps parameter into an array if it's anything else, than a numeric array
- **append** -
- **prepend** -
- **pluck** -
- **uniq** -
- **uniqByKey** -
- **sortBy** -
- **sortByKey** -
- **unnest** -
- **forEach** -
- **last** -
- **head** -
- **first** -
- **filter** -
- **find** -
- **any** -
- **includes** -
- **slice** -
- **join** -
- **pickRandom** - selects a random item from the given array
- **concat** - concatenates every argument into an array. if any of the arguments are arrays, then those will get unnested
- **zipObj** -

### String

> Most string operations come with an optional 3rd parameter called $caseSensitivity, which can be either `S::CASE_SENSITIVE` (default) or `S::CASE_INSENSITIVE`.

- **isString** -
- **length** -
- **isEmpty** -
- **isNotEmpty** -
- **toLower** -
- **toUpper** -
- **includes** -
- **split** -
- **equals** -
- **slice** -
- **startsWith** -
- **endsWith** - checks if the second parameter ends with the first

  ```php
  S::endsWith("ed", "throwed"); // -> true
  ```

  ```php
  S::endsWith("ed", "cat"); // -> false
  ```

  ```php
  S::endsWith("ED", "throwed", S::CASE_SENSITIVE); // -> false
  ```

  ```php
  S::endsWith("Ed", "tHRoWeD", S::CASE_INSENSITIVE); // true
  ```

- **trim** - removes leading and trailing whitespaces from a string

  ```php
  S::trim("  asd f     "); // -> "asd f"
  ```

- **replace** - replaces string with another

  ```php
  S::replace("a", "b", "appletini"); // -> "bppletini"
  ```

### Object

- **isObject** - check whether the passed in argument is an object

  ```php
  $point = new stdClass();
  $point->x = 10;
  $point->y = 20;
  O::isObject($point); // -> true
  ```

  ```php
  O::isObject("asdf"); // -> false
  ```

- **toPairs** - gets all keys and values of an array or object and returns it as array of key-value pairs

  ```php
  $point = new stdClass();
  $point->x = 10;
  $point->y = 20;
  O::toPairs($point); // -> [["x", 10], ["y", 20]]
  ```

  ```php
  $user = [
    "firstName" => "John",
    "lastName" => "Doe"
  ];
  O::toPairs($user); // -> [["firstName", "John"], ["lastName", "Doe"]]
  ```

  ```php
  $temperatures = [75, 44, 36];
  O::toPairs($temperatures); // -> [[0, 75], [1, 44], [2, 36]]
  ```

- **pick** -

- **assoc** - assigns value to an object via a given key. already existing keys will get overwritten

  ```php
  $point2d = new stdClass();
  $point2d->x = 10;
  $point2d->y = 20;

  $point3d = O::assoc('z', 30, $point2d); // {"x": 10, "y": 20, "z": 30}
  ```

- **dissoc** - removes a key from an object

  ```php
  $point3d = new stdClass();
  $point3d->x = 10;
  $point3d->y = 20;
  $point3d->z = 30;

  $point2d = O::dissoc('z', 30, $point3d); // {"x": 10, "y": 20}
  ```

## Future plans

I keep adding methods as I come across the need for them, so if you're missing a method you'd use, then 1) PRs are welcome, 2) Issues are welcome.

As soon as linters and other tools start recognizing [union types](https://php.watch/versions/8.0/union-types), then I'll add those in the type hinting as well.

[Mixed type](https://wiki.php.net/rfc/union_types_v2#mixed_type) will be added later to PHP, so until then type hinting will look incomplete.

## FAQ

> If the methods are all static and stateless, then why not just write simple functions?

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
  public static function includes($value, $data) {
    return mb_strpos($data, $str) !== false;
  }
}
```

If I were to have a single, combined `includes` function, then I would have to do type checking every time and it would make the code unnecessarily noisy.

Plus, sometimes the function name I would like to use is already taken by PHP, like in the case of [S::split](https://www.php.net/manual/en/function.split.php)

## Misc information

- ÁSÓ in hungarian means shovel
