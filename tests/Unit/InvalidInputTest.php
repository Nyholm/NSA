<?php

namespace Nyholm\NSA\tests\Unit;

use Nyholm\NSA;
use Nyholm\NSA\Tests\Fixture\Dog;
use PHPUnit\Framework\TestCase;

class InvalidInputTest extends TestCase
{
    public function testIntegerProperty()
    {
        $this->expectException(\InvalidArgumentException::class);
        NSA::getProperty(1, 'foo');
    }

    public function testArrayProperty()
    {
        $this->expectException(\InvalidArgumentException::class);
        NSA::getProperty(['foo' => 'bar'], 'foo');
    }

    public function testStdClassProperty()
    {
        $o = new \stdClass();
        $o->foo = 'bar';
        $this->expectException(\InvalidArgumentException::class);
        NSA::getProperty($o, 'foo');
    }

    public function testArrayPropertyValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        NSA::getProperty(new Dog(), ['foo' => 'bar']);
    }

    public function testStdClassPropertyValue()
    {
        $o = new \stdClass();
        $o->foo = 'bar';
        $this->expectException(\InvalidArgumentException::class);
        NSA::getProperty(new Dog(), $o);
    }

    public function testIntegerMethod()
    {
        $this->expectException(\InvalidArgumentException::class);
        NSA::invokeMethod(1, 'foo');
    }

    public function testArrayMethod()
    {
        $this->expectException(\InvalidArgumentException::class);
        NSA::invokeMethod(['foo' => 'bar'], 'foo');
    }

    public function testStdClassMethod()
    {
        $o = new \stdClass();
        $o->foo = function () {
            return 'bar';
        };

        $this->expectException(\InvalidArgumentException::class);
        NSA::invokeMethod($o, 'foo');
    }

    public function testIntegerMethodName()
    {
        $this->expectException(\InvalidArgumentException::class);
        NSA::invokeMethod(new Dog(), 1);
    }

    public function testArrayMethodName()
    {
        $this->expectException(\InvalidArgumentException::class);
        NSA::invokeMethod(new Dog(), ['foo' => 'bar']);
    }

    public function testStdClassMethodName()
    {
        $o = new \stdClass();
        $o->foo = function () {
            return 'bar';
        };

        $this->expectException(\InvalidArgumentException::class);
        NSA::invokeMethod(new Dog(), $o, 'foo');
    }

    public function testGetReflectionClassWithProperty()
    {
        $this->expectException(\InvalidArgumentException::class);
        NSA::invokeMethod('Nyholm\NSA', 'getReflectionClassWithProperty', 'No\Real\ClassName', 'prop');
    }

    public function testGetNotStaticPropertyWithoutObject()
    {
        $this->expectException(\InvalidArgumentException::class);
        $class = 'Nyholm\NSA\Tests\Fixture\Dog';
        NSA::getProperty($class, 'name');
    }

    public function testInvokeStaticMethodWithoutObject()
    {
        $this->expectException(\InvalidArgumentException::class);
        $class = 'Nyholm\NSA\Tests\Fixture\Dog';
        NSA::invokeMethod($class, 'bark');
    }
}
