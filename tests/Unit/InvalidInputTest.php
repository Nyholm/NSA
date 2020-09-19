<?php

namespace Nyholm\NSA\tests\Unit;

use Nyholm\NSA;
use Nyholm\NSA\Tests\Fixture\Dog;
use PHPUnit\Framework\TestCase;

class InvalidInputTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIntegerProperty()
    {
        NSA::getProperty(1, 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testArrayProperty()
    {
        NSA::getProperty(['foo' => 'bar'], 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStdClassProperty()
    {
        $o = new \stdClass();
        $o->foo = 'bar';

        NSA::getProperty($o, 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testArrayPropertyValue()
    {
        NSA::getProperty(new Dog(), ['foo' => 'bar']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStdClassPropertyValue()
    {
        $o = new \stdClass();
        $o->foo = 'bar';

        NSA::getProperty(new Dog(), $o);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIntegerMethod()
    {
        NSA::invokeMethod(1, 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testArrayMethod()
    {
        NSA::invokeMethod(['foo' => 'bar'], 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStdClassMethod()
    {
        $o = new \stdClass();
        $o->foo = function () {
            return 'bar';
        };

        NSA::invokeMethod($o, 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIntegerMethodName()
    {
        NSA::invokeMethod(new Dog(), 1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testArrayMethodName()
    {
        NSA::invokeMethod(new Dog(), ['foo' => 'bar']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStdClassMethodName()
    {
        $o = new \stdClass();
        $o->foo = function () {
            return 'bar';
        };

        NSA::invokeMethod(new Dog(), $o, 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetReflectionClassWithProperty()
    {
        NSA::invokeMethod('Nyholm\NSA', 'getReflectionClassWithProperty', 'No\Real\ClassName', 'prop');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetNotStaticPropertyWithoutObject()
    {
        $class = 'Nyholm\NSA\Tests\Fixture\Dog';
        NSA::getProperty($class, 'name');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvokeStaticMethodWithoutObject()
    {
        $class = 'Nyholm\NSA\Tests\Fixture\Dog';
        NSA::invokeMethod($class, 'bark');
    }
}
