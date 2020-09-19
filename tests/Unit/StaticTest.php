<?php

namespace Nyholm\NSA\tests\Unit;

use Nyholm\NSA;
use Nyholm\NSA\Tests\Fixture\Dog;
use PHPUnit\Framework\TestCase;

class StaticTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Dog::reset();
    }

    public function testStaticGetPrivatePropertyOnObject()
    {
        $o = new Dog();
        $result = NSA::getProperty($o, 'age');
        $this->assertEquals('initialAge', $result);
    }

    public function testStaticSetPrivatePropertyOnObject()
    {
        $o = new Dog();
        NSA::setProperty($o, 'age', 'foobar');
        $result = NSA::getProperty($o, 'age');
        $this->assertEquals('foobar', $result);
    }

    public function testStaticGetPublicPropertyOnObject()
    {
        $o = new Dog();
        $result = NSA::getProperty($o, 'birthday');
        $this->assertEquals('initialBirthday', $result);
    }

    public function testStaticSetPublicPropertyOnObject()
    {
        $o = new Dog();
        NSA::setProperty($o, 'birthday', 'foobar');
        $result = NSA::getProperty($o, 'birthday');
        $this->assertEquals('foobar', $result);
    }

    public function testStaticPrivateGetProperty()
    {
        $result = NSA::getProperty('Nyholm\NSA\Tests\Fixture\Dog', 'age');
        $this->assertEquals('initialAge', $result);
    }

    public function testStaticPrivateSetProperty()
    {
        $class = 'Nyholm\NSA\Tests\Fixture\Dog';
        NSA::setProperty($class, 'age', 'foobar');
        $result = NSA::getProperty($class, 'age');
        $this->assertEquals('foobar', $result);
    }

    public function testStaticPublicGetProperty()
    {
        $result = NSA::getProperty('Nyholm\NSA\Tests\Fixture\Dog', 'birthday');
        $this->assertEquals('initialBirthday', $result);
    }

    public function testStaticPublicSetProperty()
    {
        $class = 'Nyholm\NSA\Tests\Fixture\Dog';
        NSA::setProperty($class, 'birthday', 'foobar');
        $result = NSA::getProperty($class, 'birthday');
        $this->assertEquals('foobar', $result);
    }

    public function testInvokeStaticMethod()
    {
        $class = 'Nyholm\NSA\Tests\Fixture\Dog';
        $this->assertFalse(Dog::$eaten);
        NSA::invokeMethod($class, 'eat');
        $this->assertTrue(Dog::$eaten);
    }

    public function testInvokeStaticGetter()
    {
        $class = 'Nyholm\NSA\Tests\Fixture\Dog';
        $result = NSA::invokeMethod($class, 'staticFunc');
        $this->assertEquals('foobar', $result);
    }
}
