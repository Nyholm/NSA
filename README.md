# Simple reflection for your tests

[![Latest Version](https://img.shields.io/github/release/Nyholm/reflection-for-your-tests.svg?style=flat-square)](https://github.com/Nyholm/reflection-for-your-tests/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/Nyholm/reflection-for-your-tests.svg?style=flat-square)](https://travis-ci.org/Nyholm/reflection-for-your-tests)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Nyholm/reflection-for-your-tests.svg?style=flat-square)](https://scrutinizer-ci.com/g/Nyholm/reflection-for-your-tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/Nyholm/reflection-for-your-tests.svg?style=flat-square)](https://scrutinizer-ci.com/g/Nyholm/reflection-for-your-tests)
[![Total Downloads](https://img.shields.io/packagist/dt/nyholm/reflection-for-your-tests.svg?style=flat-square)](https://packagist.org/packages/nyholm/reflection-for-your-tests)

This small class helps you to test your private and protected properties and methods. One could argue
that one never should test private methods but sometimes it just makes the test code a lot cleaner an easier to write
and understand. This library is all about DX. 

Why "Sand"? Because sand will get in everywhere.  

## Usage

```php
$object = new Dog();

Reflection::setProperty($object, 'name', 'Foobar');
$name = Reflection::getProperty($object, 'name');
$result = Reflection::invokeMethod($object, 'doAction', 'jump', '1 meter');

echo $name; // "Foobar"
echo $result; // "Dog just did 'jump' for 1 meter"

// Access static properties and methods
$age = Reflection::getProperty('\Dog', 'age');
echo $age; // 12
```

```php
class Dog
{
    private $name = 'unnamed';
    private static $age = 12;

    private function doAction($action, $parameter)
    {
        return sprintf("Dog just did '%s' for %s", $action, $parameter);
    }
}
```

## Install

``` bash
$ composer require Nyholm/reflection-for-your-tests
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
