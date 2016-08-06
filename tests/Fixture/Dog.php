<?php

namespace Nyholm\NSA\Tests\Fixture;

class Dog extends Animal
{
    private $name = 'initName';

    protected $owner = 'initOwner';

    public $color = 'initColor';

    public $state = 'initState';

    private function setState($a, $b)
    {
        $this->state = $a.' - '.$b;
    }

    protected function changeStateToFoo()
    {
        $this->state = 'foo';
    }

    private function bark()
    {
        return 'woff';
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
}
