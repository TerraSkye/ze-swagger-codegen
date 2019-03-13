<?php

namespace spec\Swagger\V30\Schema;

use Swagger\V30\Schema\Tag;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TagSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Tag::class);
    }

    public function it_can_have_a_name()
    {
        $name = 'Name';
        $this->setName($name)->shouldBe($this);

        $this->getName()->shouldBeString();
        $this->getName()->shouldBe($name);
    }
}
