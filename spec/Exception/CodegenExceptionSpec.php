<?php

namespace spec\Swagger\Exception;

use Swagger\Exception\CodegenException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CodegenExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CodegenException::class);
    }

    public function it_can_get_a_missing_json_exception()
    {
        $this::missingSwaggerJson()->shouldBeAnInstanceOf(CodegenException::class);
    }

    public function it_can_get_a_version_detect_failure_exception()
    {
        $this::versionDetectFailure()->shouldBeAnInstanceOf(CodegenException::class);
    }

    public function it_can_get_a_missing_composer_json_exception()
    {
        $this::missingComposerJson()->shouldBeAnInstanceOf(CodegenException::class);
    }

    public function it_can_get_a_invalid_composer_json_exception()
    {
        $this::invalidComposerJson("Json is invalid")->shouldBeAnInstanceOf(CodegenException::class);
    }

    public function it_can_get_a_missing_composer_autoloader_exception()
    {
        $this::missingComposerAutoloaders()->shouldBeAnInstanceOf(CodegenException::class);
    }

    public function it_can_get_an_autoloader_not_found_exception()
    {
        $this::autoloaderNotFound(CodegenException::class)->shouldBeAnInstanceOf(CodegenException::class);
    }

    public function it_can_get_a_missing_yaml_extension_exception()
    {
        $this::missingYamlExtension(CodegenException::class)->shouldBeAnInstanceOf(CodegenException::class);
    }

    public function it_can_get_a_unknown_file_extension_exception()
    {
        $this::unknownFileExtension(CodegenException::class)->shouldBeAnInstanceOf(CodegenException::class);
    }
}
