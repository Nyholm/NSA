<?php

namespace Nyholm\Reflection\tests\Unit;

use Nyholm\Reflection\Reflection;
use Nyholm\Reflection\Tests\Fixture\Dog;

class StaticTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        Dog::reset();
    }

    public function testStaticGetPrivatePropertyOnObject()
    {
        $o = new Dog();
        $result = Reflection::getProperty($o, 'age');
        $this->assertEquals('initialAge', $result);
    }

    public function testStaticSetPrivatePropertyOnObject()
    {
        $o = new Dog();
        Reflection::setProperty($o, 'age', 'foobar');
        $result = Reflection::getProperty($o, 'age');
        $this->assertEquals('foobar', $result);
    }

    public function testStaticGetPublicPropertyOnObject()
    {
        $o = new Dog();
        $result = Reflection::getProperty($o, 'birthday');
        $this->assertEquals('initialBirthday', $result);
    }

    public function testStaticSetPublicPropertyOnObject()
    {
        $o = new Dog();
        Reflection::setProperty($o, 'birthday', 'foobar');
        $result = Reflection::getProperty($o, 'birthday');
        $this->assertEquals('foobar', $result);
    }

    public function testStaticPrivateGetProperty()
    {
        $result = Reflection::getProperty('Nyholm\Reflection\Tests\Fixture\Dog', 'age');
        $this->assertEquals('initialAge', $result);
    }

    public function testStaticPrivateSetProperty()
    {
        $class = 'Nyholm\Reflection\Tests\Fixture\Dog';
        Reflection::setProperty($class, 'age', 'foobar');
        $result = Reflection::getProperty($class, 'age');
        $this->assertEquals('foobar', $result);
    }

    public function testStaticPublicGetProperty()
    {
        $result = Reflection::getProperty('Nyholm\Reflection\Tests\Fixture\Dog', 'birthday');
        $this->assertEquals('initialBirthday', $result);
    }

    public function testStaticPublicSetProperty()
    {
        $class = 'Nyholm\Reflection\Tests\Fixture\Dog';
        Reflection::setProperty($class, 'birthday', 'foobar');
        $result = Reflection::getProperty($class, 'birthday');
        $this->assertEquals('foobar', $result);
    }

    public function testInvokeStaticMethod()
    {
        $class = 'Nyholm\Reflection\Tests\Fixture\Dog';
        $this->assertFalse(Dog::$eaten);
        Reflection::invokeMethod($class, 'eat');
        $this->assertTrue(Dog::$eaten);
    }

    public function testInvokeStaticGetter()
    {
        $class = 'Nyholm\Reflection\Tests\Fixture\Dog';
        $result = Reflection::invokeMethod($class, 'staticFunc');
        $this->assertEquals('foobar', $result);
    }
}
