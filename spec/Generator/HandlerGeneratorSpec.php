<?php

namespace spec\Swagger\Generator;

use Swagger\Generator\HandlerGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Swagger\Template;
use Swagger\V30\Schema\Document;
use Swagger\V30\Schema\PathItem;
use org\bovigo\vfs\vfsStream;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class HandlerGeneratorSpec extends ObjectBehavior
{
    public function let(Template $templateService)
    {
        $this->beConstructedWith($templateService);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(HandlerGenerator::class);
    }

    public function it_can_generate_from_path_item(PathItem $pathItem, Template $templateService)
    {
        vfsStream::setup('namespacePath');

        $templateService->render('handler', [
            'className'  => "TestHandler",
            'namespace'  => 'App\Handler',
            'operationMethods' => []
        ])->willReturn(Argument::type('string'));
        $templateService->render('handler', [
            'className'  => "TestHandler",
            'namespace'  => 'App\Handler',
            'operationMethods' => []
        ])->shouldBeCalled();

        $pathItem->getOperations()->willReturn([]);

        $this->generateFromPathItem($pathItem, 'test', vfsStream::url('namespacePath'), 'App')->shouldBeString();
    }

    public function it_can_generate_from_document(Document $document, PathItem $pathItem, Template $templateService)
    {
        vfsStream::setup('namespacePath');

        $document->getPaths()->willReturn([
            'test' => $pathItem
        ]);

        $templateService->render('handler', [
            'className'  => "TestHandler",
            'namespace'  => 'App\Handler',
            'operationMethods' => []
        ])->willReturn(Argument::type('string'));
        $templateService->render('handler', [
            'className'  => "TestHandler",
            'namespace'  => 'App\Handler',
            'operationMethods' => []
        ])->shouldBeCalled();

        $pathItem->getOperations()->willReturn([]);

        $this->generateFromDocument($document, vfsStream::url('namespacePath'), 'App');
    }
}
