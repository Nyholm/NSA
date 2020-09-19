<?php

namespace Nyholm\NSA\tests\Unit;

use Nyholm\NSA;
use Nyholm\NSA\Tests\Fixture\Dog;
use PHPUnit\Framework\TestCase;

class GetPropertiesTest extends TestCase
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

        return [
            ['Nyholm\NSA\Tests\Fixture\Dog', 'name'],
            ['Nyholm\NSA\Tests\Fixture\Dog', 'owner'],
            ['Nyholm\NSA\Tests\Fixture\Dog', 'owner'],
            ['Nyholm\NSA\Tests\Fixture\Dog', 'color'],
            ['Nyholm\NSA\Tests\Fixture\Dog', 'latinName'],
            ['Nyholm\NSA\Tests\Fixture\Dog', 'age'],
            ['Nyholm\NSA\Tests\Fixture\Dog', 'birthday'],
            ['Nyholm\NSA\Tests\Fixture\Dog', 'count'],
            [$dog, 'name'],
            [$dog, 'owner'],
            [$dog, 'owner'],
            [$dog, 'color'],
            [$dog, 'latinName'],
            [$dog, 'age'],
            [$dog, 'birthday'],
            [$dog, 'count'],
        ];
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
