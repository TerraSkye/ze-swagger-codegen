<?php

namespace spec\Swagger;

use Swagger\Composer;
use PhpSpec\ObjectBehavior;
use Swagger\Exception\CodegenException;
use org\bovigo\vfs\vfsStream;

class ComposerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Composer::class);
    }

    public function it_can_get_composer_autoloaders()
    {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/data/openapi-composer'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $this->getComposerAutoloaders($projectRoot)->shouldBeArray();
    }

    public function it_can_get_composer_autoloaders_from_vendor()
    {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/data/openapi-composer-vendor'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $this->getComposerAutoloaders($projectRoot)->shouldBeArray();
        $this->getComposerAutoloaders($projectRoot)->shouldBe([
            'App\\' => 'src/'
        ]);
    }

    public function it_cant_get_composer_autoloaders_because_of_missing_composer_json()
    {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/data/only-openapi'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $this->shouldThrow(new CodegenException('Could not find a composer.json in the project root'))->during('getComposerAutoloaders', [$projectRoot]);
    }

    public function it_cant_get_composer_autoloaders_because_of_invalid_composer_json()
    {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/data/only-openapi'), $vfsProjectRoot);

        $projectRoot = vfsStream::url('projectRoot');

        vfsStream::newFile('composer.json')->at($vfsProjectRoot);

        $this->shouldThrow(new CodegenException('Unable to parse composer.json: Syntax error'))->during('getComposerAutoloaders', [$projectRoot]);
    }

    public function it_cant_execute_the_command_because_of_missing_psr4_autoloader()
    {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/data/only-openapi'), $vfsProjectRoot);

        $projectRoot = vfsStream::url('projectRoot');

        vfsStream::newFile('composer.json')->withContent('{"autoload":{}}')->at($vfsProjectRoot);

        $this->shouldThrow(new CodegenException('composer.json does not define any PSR-4 autoloaders'))->during('getComposerAutoloaders', [$projectRoot]);
    }

    public function it_cant_execute_the_command_because_of_invalid_psr4_autoloader()
    {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/data/only-openapi'), $vfsProjectRoot);

        $projectRoot = vfsStream::url('projectRoot');

        vfsStream::newFile('composer.json')->withContent('{"autoload":{"psr-4":"invalid"}}')->at($vfsProjectRoot);

        $this->shouldThrow(new CodegenException('composer.json does not define any PSR-4 autoloaders'))->during('getComposerAutoloaders', [$projectRoot]);
    }
}
