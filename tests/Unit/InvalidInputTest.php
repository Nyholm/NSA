<?php

namespace Nyholm\Reflection\tests\Unit;

use Nyholm\Reflection\Reflection;
use Nyholm\Reflection\Tests\Fixture\Dog;

class InvalidInputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIntegerProperty()
    {
        Reflection::getProperty(1, 'foo');
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testArrayProperty()
    {
        Reflection::getProperty(array('foo' => 'bar'), 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStdClassProperty()
    {
        $o = new \stdClass();
        $o->foo = 'bar';

        Reflection::getProperty($o, 'foo');
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testArrayPropertyValue()
    {
        Reflection::getProperty(new Dog(), array('foo' => 'bar'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStdClassPropertyValue()
    {
        $o = new \stdClass();
        $o->foo = 'bar';

        Reflection::getProperty(new Dog(), $o);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIntegerMethod()
    {
        Reflection::invokeMethod(1, 'foo');
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testArrayMethod()
    {
        Reflection::invokeMethod(array('foo' => 'bar'), 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStdClassMethod()
    {
        $o = new \stdClass();
        $o->foo = function () { return 'bar'; };

        Reflection::invokeMethod($o, 'foo');
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIntegerMethodName()
    {
        Reflection::invokeMethod(new Dog(), 1);
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testArrayMethodName()
    {
        Reflection::invokeMethod(new Dog(), array('foo' => 'bar'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStdClassMethodName()
    {
        $o = new \stdClass();
        $o->foo = function () { return 'bar'; };

        Reflection::invokeMethod(new Dog(), $o, 'foo');
    }

    public function testGetReflectionClassWithProperty()
    {
        // TODO test with invlaid class
    }
}
