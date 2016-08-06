<?php

namespace Nyholm\Reflection\tests\Unit;

use Nyholm\Reflection\Reflection;
use Nyholm\Reflection\Tests\Fixture\Dog;

class CatchNameReflectionMethodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testInvokeMethodNoArguments()
    {
        Reflection::invokeMethod();
    }

    /**
     * @expectedException \LogicException
     */
    public function testInvokeMethodOneArguments()
    {
        $o = new Dog();
        Reflection::invokeMethod($o);
    }

    public function testInvokePrivateSetter()
    {
        $o = new Dog();
        $this->assertEquals('initState', $o->getState());
        $result = Reflection::invokeMethod($o, 'setState', 'foo', 'bar');
        $this->assertNull($result);
        $this->assertEquals('foo - bar', $o->getState());
    }

    public function testInvokePrivateGetter()
    {
        $o = new Dog();
        $result = Reflection::invokeMethod($o, 'bark');
        $this->assertEquals('woff', $result);
    }

    public function testInvokeParentMethod()
    {
        $o = new Dog();
        $this->assertTrue($o->alive);
        Reflection::invokeMethod($o, 'kill');
        $this->assertFalse($o->alive);
    }
}
