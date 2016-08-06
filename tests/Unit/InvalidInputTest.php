<?php

namespace Nyholm\Reflection\tests\Unit;

use Nyholm\Reflection\Reflection;
use Nyholm\Reflection\Tests\Fixture\Dog;

class InvalidInputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testIntegerProperty()
    {
        Reflection::getProperty(1, 'foo');
    }
    /**
     * @expectedException \LogicException
     */
    public function testArrayProperty()
    {
        Reflection::getProperty(array('foo' => 'bar'), 'foo');
    }

    /**
     * @expectedException \LogicException
     */
    public function testStdClassProperty()
    {
        $o = new \stdClass();
        $o->foo = 'bar';

        Reflection::getProperty($o, 'foo');
    }

    /**
     * @expectedException \LogicException
     */
    public function testIntegerMethod()
    {
        Reflection::invokeMethod(1, 'foo');
    }
    /**
     * @expectedException \LogicException
     */
    public function testArrayMethod()
    {
        Reflection::invokeMethod(array('foo' => 'bar'), 'foo');
    }

    /**
     * @expectedException \LogicException
     */
    public function testStdClassMethod()
    {
        $o = new \stdClass();
        $o->foo = function () { return 'bar'; };

        Reflection::invokeMethod($o, 'foo');
    }
    /**
     * @expectedException \LogicException
     */
    public function testIntegerMethodName()
    {
        Reflection::invokeMethod(new Dog(), 1);
    }
    /**
     * @expectedException \LogicException
     */
    public function testArrayMethodName()
    {
        Reflection::invokeMethod(new Dog(), array('foo' => 'bar'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testStdClassMethodName()
    {
        $o = new \stdClass();
        $o->foo = function () { return 'bar'; };

        Reflection::invokeMethod(new Dog(), $o, 'foo');
    }
}
