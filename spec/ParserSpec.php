<?php

namespace spec\Swagger;

use Prophecy\Argument;
use Swagger\Exception\CodegenException;
use Swagger\Parser;
use PhpSpec\ObjectBehavior;
use Swagger\V30\Hydrator\DocumentHydrator;
use Swagger\V30\Schema\Document;
use org\bovigo\vfs\vfsStream;

class ParserSpec extends ObjectBehavior
{
    public function let(DocumentHydrator $documentHydrator)
    {
        $this->beConstructedWith($documentHydrator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Parser::class);
    }

    public function it_can_parse_a_json_file(
        DocumentHydrator $documentHydrator,
        Document $document
    ) {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/data/openapi-composer'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $documentHydrator->hydrate(Argument::type('array'), Argument::type(Document::class))->willReturn($document);
        $documentHydrator->hydrate(Argument::type('array'), Argument::type(Document::class))->shouldBeCalled();

        $this->parseFile($projectRoot . '/openapi.json')->shouldBe($document);
    }

    public function it_can_parse_a_yaml_file(
        DocumentHydrator $documentHydrator,
        Document $document
    ) {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/data/openapi-composer'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $data = json_decode(file_get_contents($projectRoot . '/openapi.json'), true);

        vfsStream::newFile('openapi.yml')->withContent(yaml_emit($data))->at($vfsProjectRoot);

        $documentHydrator->hydrate(Argument::type('array'), Argument::type(Document::class))->willReturn($document);
        $documentHydrator->hydrate(Argument::type('array'), Argument::type(Document::class))->shouldBeCalled();

        $this->parseFile($projectRoot . '/openapi.yml')->shouldBe($document);
    }

    public function it_cant_parse_the_file_because_of_an_invalid_extension()
    {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/data/openapi-composer'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        vfsStream::newFile('openapi.jpg')->withContent('{}')->at($vfsProjectRoot);

        $this->shouldThrow(CodegenException::class)->during('parseFile', [
            $projectRoot . '/openapi.jpg'
        ]);
    }

    public function it_can_detect_the_openapi_version_for_swagger()
    {
        $this->detectOpenAPIVersion([
            'swagger' => '2.0'
        ])->shouldBeString();
        $this->detectOpenAPIVersion([
            'swagger' => '2.0'
        ])->shouldBe('2.0');
    }

    public function it_can_detect_the_openapi_version_for_openapi()
    {
        $this->detectOpenAPIVersion([
            'openapi' => '3.0'
        ])->shouldBeString();
        $this->detectOpenAPIVersion([
            'openapi' => '3.0'
        ])->shouldBe('3.0');
    }

    public function it_cant_detect_the_openapi_version()
    {
        $this->shouldThrow(CodegenException::class)->during('detectOpenAPIVersion', [[]]);
    }
}
