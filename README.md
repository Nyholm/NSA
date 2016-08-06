# NSA; See and do whatever you want. Great for writing tests

[![Latest Version](https://img.shields.io/github/release/Nyholm/nsa.svg?style=flat-square)](https://github.com/Nyholm/nsa/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/Nyholm/nsa.svg?style=flat-square)](https://travis-ci.org/Nyholm/nsa)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Nyholm/nsa.svg?style=flat-square)](https://scrutinizer-ci.com/g/Nyholm/nsa)
[![Quality Score](https://img.shields.io/scrutinizer/g/Nyholm/nsa.svg?style=flat-square)](https://scrutinizer-ci.com/g/Nyholm/nsa)
[![Total Downloads](https://img.shields.io/packagist/dt/nyholm/nsa.svg?style=flat-square)](https://packagist.org/packages/nyholm/nsa)

This small class helps you to test your private and protected properties and methods. One could argue that you never 
should test private methods but sometimes it just makes the test code a lot cleaner an easier to write
and understand. This library is all about DX. 


## Usage

```php
$object = new Dog();

NSA::setProperty($object, 'name', 'Foobar');
$name = NSA::getProperty($object, 'name');
$result = NSA::invokeMethod($object, 'doAction', 'jump', '1 meter');

echo $name; // "Foobar"
echo $result; // "Dog just did 'jump' for 1 meter"

// Access static properties and methods
$age = NSA::getProperty('\Dog', 'age');
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
$ composer require Nyholm/nsa
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
