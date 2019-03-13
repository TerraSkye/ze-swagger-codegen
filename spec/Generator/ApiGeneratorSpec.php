<?php

namespace spec\Swagger\Generator;

use Swagger\Generator\ApiGenerator;
use PhpSpec\ObjectBehavior;
use Swagger\Template;

class ApiGeneratorSpec extends ObjectBehavior
{
    /**
     * @param  Template         $templateService
     */
    public function let(
        Template $templateService
    ) {
        $this->beConstructedWith($templateService);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ApiGenerator::class);
    }
}
