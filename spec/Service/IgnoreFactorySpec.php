<?php

namespace spec\Swagger\Service;

use Swagger\Ignore;
use Swagger\Service\IgnoreFactory;
use PhpSpec\ObjectBehavior;

class IgnoreFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(IgnoreFactory::class);
    }

    public function it_can_create_the_service()
    {
        $this->__invoke()->shouldBeAnInstanceOf(Ignore::class);
    }
}
