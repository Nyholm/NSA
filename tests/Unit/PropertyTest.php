<?php

namespace Nyholm\Reflection\Tests\Unit;

use Nyholm\Reflection\Reflection;
use Nyholm\Reflection\Tests\Fixture\Dog;

class PropertyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testGetPropertyNotExist()
    {
        $o = new Dog();
        Reflection::getProperty($o, 'noProperty');
    }

    public function testGetPrivateProperty()
    {
        $o = new Dog();
        $result = Reflection::getProperty($o, 'name');
        $this->assertEquals('initName', $result);
    }

    public function testGetProtectedProperty()
    {
        $o = new Dog();
        $result = Reflection::getProperty($o, 'owner');
        $this->assertEquals('initOwner', $result);
    }

    public function testGetPublicProperty()
    {
        $o = new Dog();
        $result = Reflection::getProperty($o, 'color');
        $this->assertEquals('initColor', $result);
    }

    public function testSetPrivateProperty()
    {
        $o = new Dog();
        Reflection::setProperty($o, 'name', 'foobar');
        $result = Reflection::getProperty($o, 'name');

        $this->assertEquals('foobar', $result);
    }

    public function testSetProtectedProperty()
    {
        $o = new Dog();
        Reflection::setProperty($o, 'owner', 'foobar');
        $result = Reflection::getProperty($o, 'owner');
        $this->assertEquals('foobar', $result);
    }

    public function testSetPublicProperty()
    {
        $o = new Dog();
        Reflection::setProperty($o, 'color', 'foobar');
        $result = Reflection::getProperty($o, 'color');
        $this->assertEquals('foobar', $result);
    }

    public function testGetParentPrivateProperty()
    {
        $o = new Dog();
        $result = Reflection::getProperty($o, 'latinName');
        $this->assertEquals('initLatinName', $result);
    }

    public function testSetParentPrivateProperty()
    {
        $o = new Dog();
        Reflection::setProperty($o, 'latinName', 'foobar');
        $result = Reflection::getProperty($o, 'latinName');
        $this->assertEquals('foobar', $result);
    }

    public function testGetGrandParentPrivateProperty()
    {
        $o = new Dog();
        $result = Reflection::getProperty($o, 'count');
        $this->assertEquals('initCount', $result);
    }

    public function testSetGrandParentPrivateProperty()
    {
        $o = new Dog();
        Reflection::setProperty($o, 'count', 'foobar');
        $result = Reflection::getProperty($o, 'count');
        $this->assertEquals('foobar', $result);
    }
}
