<?php

namespace Nyholm\Reflection\Tests\Fixture;

class Animal extends Thing
{
    private $latinName = 'initLatinName';

    public $alive = true;

    private function setState($a, $b)
    {
        $this->state = 'Animal set state: '.$a;
    }

    private function kill()
    {
        $this->alive = false;
    }
}
