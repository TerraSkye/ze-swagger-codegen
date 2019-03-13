<?php

namespace spec\Swagger;

use Swagger\Ignore;
use PhpSpec\ObjectBehavior;

class IgnoreSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            __DIR__ . DIRECTORY_SEPARATOR . '.swagger-codegen-ignore'
        ]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Ignore::class);
    }

    public function it_can_read_an_empty_ignore_file()
    {
        $this->beConstructedWith([
            __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . '.swagger-codegen-ignore-empty'
        ]);
    }

    public function it_can_read_an_ignore_file_and_check_for_a_ignored_path()
    {
        $ignoredPath = __DIR__ . DIRECTORY_SEPARATOR . 'Exception' . DIRECTORY_SEPARATOR . 'CodegenExceptionSpec.php';

        $this->isIgnored($ignoredPath)->shouldBeBool();
        $this->isIgnored($ignoredPath)->shouldBe(true);
    }
}
