<?php

namespace Nyholm\NSA\tests\Unit;

use Nyholm\NSA;
use Nyholm\NSA\Tests\Fixture\Dog;
use PHPUnit\Framework\TestCase;

class ConstantTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testGetConstantNotExist()
    {
        $o = new Dog();
        NSA::getConstant($o, 'INEXISTENT_CONSTANT');
    }

    public function testGetsPrivateConstantByObject()
    {
        $o = new Dog();
        $result = NSA::getConstant($o, 'PRIVATE_CONSTANT');
        $this->assertEquals('initConstant', $result);
    }

    public function testGetsPrivateConstantByClassName()
    {
        $result = NSA::getConstant(Dog::class, 'PRIVATE_CONSTANT');
        $this->assertEquals('initConstant', $result);
    }
}
