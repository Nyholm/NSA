<?php

namespace Nyholm\NSA\tests\Unit;

use Nyholm\NSA;
use Nyholm\NSA\Tests\Fixture\Dog;

class ConstantTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testGetConstantNotExist()
    {
        $o = new Dog();
        NSA::getConstant($o, 'INEXISTENT_CONSTANT');
    }

    public function testGetsPrivateConstant()
    {
        $o = new Dog();
        $result = NSA::getConstant($o, 'PRIVATE_CONSTANT');
        $this->assertEquals('initConstant', $result);
    }
}
