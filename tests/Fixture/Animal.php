<?php

namespace Nyholm\NSA\Tests\Fixture;

class Animal extends Thing
{
    private static $age = 'initialAge';
    public static $birthday = 'initialBirthday';

    private $latinName = 'initLatinName';

    public $alive = true;

    public static $eaten = false;

    private function setState($a, $b)
    {
        $this->state = 'Animal set state: '.$a;
    }

    private function kill()
    {
        $this->alive = false;
    }

    private static function eat()
    {
        self::$eaten = true;
    }

    private static function staticFunc()
    {
        return 'foobar';
    }

    public static function reset()
    {
        self::$age = 'initialAge';
        self::$birthday = 'initialBirthday';
        self::$eaten = false;
    }
}
