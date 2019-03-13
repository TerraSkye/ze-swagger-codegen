<?php

namespace spec\Swagger\Generator;

use Prophecy\Argument;
use Swagger\Generator\ModelGenerator;
use Swagger\Generator\RoutesGenerator;
use PhpSpec\ObjectBehavior;
use Swagger\Generator\HandlerGenerator;
use Swagger\Ignore;
use Swagger\Template;
use Swagger\V30\Schema\PathItem;
use Swagger\V30\Schema\MediaType;
use Swagger\V30\Schema\Operation;
use Swagger\V30\Schema\Reference;
use Swagger\V30\Schema\RequestBody;
use org\bovigo\vfs\vfsStream;
use Swagger\V30\Schema\Document;

class RoutesGeneratorSpec extends ObjectBehavior
{
    /**
     * @param  Template         $templateService
     * @param  HandlerGenerator $handlerGenerator
     * @param  ModelGenerator   $modelGenerator
     * @param  Ignore           $ignoreService
     */
    public function let(
        Template $templateService,
        HandlerGenerator $handlerGenerator,
        ModelGenerator $modelGenerator,
        Ignore $ignoreService
    ) {
        $this->beConstructedWith($templateService, $handlerGenerator, $modelGenerator, $ignoreService);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RoutesGenerator::class);
    }

    /**
     * @param  Document         $document
     * @param  Ignore           $ignoreService
     * @param  Template         $templateService
     * @param  PathItem         $pathItem
     * @param  Operation        $operation
     * @param  HandlerGenerator $handlerGenerator
     * @param  ModelGenerator   $modelGenerator
     * @param  RequestBody      $requestBody
     * @param  MediaType        $mediaType
     * @param  Reference        $reference
     */
    public function it_can_generate_from_document(
        Document $document,
        Ignore $ignoreService,
        Template $templateService,
        PathItem $pathItem,
        Operation $operation,
        HandlerGenerator $handlerGenerator,
        ModelGenerator $modelGenerator,
        RequestBody $requestBody,
        MediaType $mediaType,
        Reference $reference
    ) {
        vfsStream::setup('namespacePath');

        $namespace = 'App';
        $middleware = "App\Handler\PathHandler::class";
        $modelMiddleware = "App\Model\Reference::class";
        $path = '/path/';
        $method = "GET";
        $operationId = "OperationId";

        $ignoreService->isIgnored(Argument::type('string'))->willReturn(false);
        $ignoreService->isIgnored(Argument::type('string'))->shouldBeCalled();

        $templateService->render('routes', [
            'routes'  => [[
                "middleware" => $middleware,
                "modelMiddleware" => $modelMiddleware,
                "path" => $path,
                "method" => $method,
                "name" => $operationId]]
        ])->willReturn(Argument::type('string'));
        $templateService->render('routes', [
            'routes'  => [[
                "middleware" => $middleware,
                "modelMiddleware" => $modelMiddleware,
                "path" => $path,
                "method" => $method,
                "name" => $operationId]]
        ])->shouldBeCalled();

        $document->getPaths()->willReturn([
            $path => $pathItem
        ]);

        $pathItem->getOperations()->willReturn([
            $method => $operation
        ]);

        $operation->getOperationId()->willReturn($operationId);
        $operation->getRequestBody()->willReturn($requestBody);

        $requestBody->getContent()->willReturn([
            'application/json' => $mediaType
        ]);

        $mediaType->getSchema()->willReturn($reference);
        $reference->getRef()->willReturn('#/components/schemas/Reference');

        $handlerGenerator->getNamespace($namespace)->willReturn($namespace . '\Handler');
        $handlerGenerator->getHandlerName($path)->willReturn('PathHandler');
        $modelGenerator->getNamespace($namespace)->willReturn($namespace . '\Model');

        $this->generateFromDocument($document, $namespace, vfsStream::url('namespacePath'))->shouldBe(true);
        $this->generateFromDocument($document, $namespace, vfsStream::url('namespacePath'))->shouldBeBool();
    }

    /**
     * @param  Document         $document
     * @param  Ignore           $ignoreService
     */
    public function it_doesnt_generate_because_of_ignore(
        Document $document,
        Ignore $ignoreService
    ) {
        $namespace = 'App';

        $ignoreService->isIgnored(Argument::type('string'))->willReturn(true);
        $ignoreService->isIgnored(Argument::type('string'))->shouldBeCalled();

        $this->generateFromDocument($document, $namespace, vfsStream::url('namespacePath'))->shouldBe(false);
        $this->generateFromDocument($document, $namespace, vfsStream::url('namespacePath'))->shouldBeBool();
    }
}
