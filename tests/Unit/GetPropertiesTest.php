<?php

namespace Nyholm\NSA\tests\Unit;

use Nyholm\NSA;
use Nyholm\NSA\Tests\Fixture\Dog;

class GetPropertiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIntegerProperty()
    {
        NSA::getProperties(1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStdClassProperty()
    {
        $o = new \stdClass();
        $o->foo = 'bar';

        NSA::getProperties($o);
    }

    public function propertyDataProvider()
    {
        $dog = new Dog();
        return array(
            array('Nyholm\NSA\Tests\Fixture\Dog', 'name'),
            array('Nyholm\NSA\Tests\Fixture\Dog', 'owner'),
            array('Nyholm\NSA\Tests\Fixture\Dog', 'owner'),
            array('Nyholm\NSA\Tests\Fixture\Dog', 'color'),
            array('Nyholm\NSA\Tests\Fixture\Dog', 'latinName'),
            array('Nyholm\NSA\Tests\Fixture\Dog', 'age'),
            array('Nyholm\NSA\Tests\Fixture\Dog', 'birthday'),
            array('Nyholm\NSA\Tests\Fixture\Dog', 'count'),
            array($dog, 'name'),
            array($dog, 'owner'),
            array($dog, 'owner'),
            array($dog, 'color'),
            array($dog, 'latinName'),
            array($dog, 'age'),
            array($dog, 'birthday'),
            array($dog, 'count'),
        );
    }

    /**
     * @dataProvider propertyDataProvider
     */
    public function testListProperties($classOrObject, $propertyName)
    {
        $result = NSA::getProperties($classOrObject);

        $message = sprintf('Count not find property "%s" in array [%s]', $propertyName, implode(', ', $result));
        $this->assertTrue(in_array($propertyName, $result), $message);
    }
}
