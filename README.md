# php-a-s-o

Yet another collection of PHP functions for manipulating Arrays, Strings and Objects - based on [ramda.js](https://ramdajs.com/)

Because [fuck](http://phpsadness.com/sad/4) [PHP](http://phpsadness.com/sad/15) [and](http://phpsadness.com/sad/48) [it's](http://phpsadness.com/sad/6) [inconsistent](http://phpsadness.com/sad/9) [functions](http://phpsadness.com/sad/8)!

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

## Future plans

I plan on adding type hinting, where possible.

Also, I keep adding methods as I come across the need for them, so if you don't see a method you would like to use, then 1) PRs are welcome, 2) Issues are welcome.

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

## Licence

Do whatever you like with the code
