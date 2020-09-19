<?php

namespace Nyholm\NSA\Tests\Unit;

use Nyholm\NSA;
use Nyholm\NSA\Tests\Fixture\Dog;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    public function testGetPropertyNotExist()
    {
        $o = new Dog();
        $this->expectException(\LogicException::class);
        NSA::getProperty($o, 'noProperty');
    }

    public function testGetPrivateProperty()
    {
        $o = new Dog();
        $result = NSA::getProperty($o, 'name');
        $this->assertEquals('initName', $result);
    }

    public function testGetProtectedProperty()
    {
        $o = new Dog();
        $result = NSA::getProperty($o, 'owner');
        $this->assertEquals('initOwner', $result);
    }

    public function testGetPublicProperty()
    {
        $o = new Dog();
        $result = NSA::getProperty($o, 'color');
        $this->assertEquals('initColor', $result);
    }

    public function testSetPrivateProperty()
    {
        $o = new Dog();
        NSA::setProperty($o, 'name', 'foobar');
        $result = NSA::getProperty($o, 'name');

        $this->assertEquals('foobar', $result);
    }

    public function testSetProtectedProperty()
    {
        $o = new Dog();
        NSA::setProperty($o, 'owner', 'foobar');
        $result = NSA::getProperty($o, 'owner');
        $this->assertEquals('foobar', $result);
    }

    public function testSetPublicProperty()
    {
        $o = new Dog();
        NSA::setProperty($o, 'color', 'foobar');
        $result = NSA::getProperty($o, 'color');
        $this->assertEquals('foobar', $result);
    }

    public function testGetParentPrivateProperty()
    {
        $o = new Dog();
        $result = NSA::getProperty($o, 'latinName');
        $this->assertEquals('initLatinName', $result);
    }

    public function testSetParentPrivateProperty()
    {
        $o = new Dog();
        NSA::setProperty($o, 'latinName', 'foobar');
        $result = NSA::getProperty($o, 'latinName');
        $this->assertEquals('foobar', $result);
    }

    public function testGetGrandParentPrivateProperty()
    {
        $o = new Dog();
        $result = NSA::getProperty($o, 'count');
        $this->assertEquals('initCount', $result);
    }

    public function testSetGrandParentPrivateProperty()
    {
        $o = new Dog();
        NSA::setProperty($o, 'count', 'foobar');
        $result = NSA::getProperty($o, 'count');
        $this->assertEquals('foobar', $result);
    }
}
