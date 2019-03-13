<?php

namespace spec\Swagger\V30\Schema;

use Swagger\V30\Schema\Server;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Swagger\V30\Schema\ServerVariable;

class ServerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Server::class);
    }

    public function it_can_add_a_variable(ServerVariable $variable)
    {
        $this->getVariables()->shouldNotContain($variable);

        $this->addVariable($variable)->shouldBe($this);

        $this->getVariables()->shouldContain($variable);
    }
}
