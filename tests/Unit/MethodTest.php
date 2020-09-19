<?php

namespace Nyholm\NSA\tests\Unit;

use Nyholm\NSA;
use Nyholm\NSA\Tests\Fixture\Dog;
use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testInvokeMethodNoArguments()
    {
        NSA::invokeMethod();
    }

    /**
     * @expectedException \LogicException
     */
    public function testInvokeMethodOneArguments()
    {
        $o = new Dog();
        NSA::invokeMethod($o);
    }

    /**
     * @expectedException \LogicException
     */
    public function testInvokeMethodNotExist()
    {
        $o = new Dog();
        NSA::invokeMethod($o, 'noMethod');
    }

    public function testInvokePrivateSetter()
    {
        $o = new Dog();
        $this->assertEquals('initState', $o->getState());
        $result = NSA::invokeMethod($o, 'setState', 'foo', 'bar');
        $this->assertNull($result);
        $this->assertEquals('foo - bar', $o->getState());
    }

    public function testInvokePrivateGetter()
    {
        $o = new Dog();
        $result = NSA::invokeMethod($o, 'bark');
        $this->assertEquals('woff', $result);
    }

    public function testInvokeParentMethod()
    {
        $o = new Dog();
        $this->assertTrue($o->alive);
        NSA::invokeMethod($o, 'kill');
        $this->assertFalse($o->alive);
    }
}
