<?php

namespace spec\Swagger\Generator;

use Swagger\Ignore;

use Swagger\Generator\HydratorGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use org\bovigo\vfs\vfsStream;
use Swagger\Template;
use Swagger\V30\Schema\Document;
use Swagger\V30\Schema\Schema;
use Swagger\V30\Schema\Reference;
use Swagger\Generator\ModelGenerator;
use Swagger\Ignore;
use Swagger\V30\Schema\Components;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class HydratorGeneratorSpec extends ObjectBehavior
{
    public function let(
        Template $templateService,
        ModelGenerator $modelGenerator,
        Ignore $ignoreService
    ) {
        $this->beConstructedWith($templateService, $modelGenerator, $ignoreService);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(HydratorGenerator::class);
    }

    public function it_can_generate_from_schema(
        Schema $schema,
        ModelGenerator $modelGenerator,
        Template $templateService,
        Ignore $ignoreService
    ) {
        vfsStream::setup('namespacePath');

        $namespace = 'App';

        $modelGenerator->getNamespace($namespace)->willReturn($namespace . '\Model');
        $modelGenerator->getModelProperties($schema, 'test')->willReturn([]);

        $templateService->render('model-hydrator', [
            'className'  => "TestHydrator",
            'namespace'  => $namespace . '\Hydrator',
            'modelName' => 'Test',
            'modelNamespace' => $namespace . '\Model',
            'properties' => []
        ])->willReturn(Argument::type('string'));
        $templateService->render('model-hydrator', [
            'className'  => "TestHydrator",
            'namespace'  => $namespace . '\Hydrator',
            'modelName' => 'Test',
            'modelNamespace' => $namespace . '\Model',
            'properties' => []
        ])->shouldBeCalled();

        $ignoreService->isIgnored(Argument::type('string'))->willReturn(false);
        $ignoreService->isIgnored(Argument::type('string'))->shouldBeCalled();

        $this->generateFromSchema($schema, 'test', vfsStream::url('namespacePath'), $namespace)->shouldBeString();
    }

    public function it_can_get_namespace()
    {
        $this->getNamespace('App')->shouldReturn('App\Hydrator');
        $this->getNamespace('Test')->shouldReturn('Test\Hydrator');
    }

    public function it_can_generate_from_document(
        Document $document,
        Components $components,
        Schema $schema,
        Template $templateService,
        ModelGenerator $modelGenerator,
        Ignore $ignoreService
    ) {
        vfsStream::setup('namespacePath');

        $modelName = 'Test';
        $namespace = 'App';

        $document->getComponents()->willReturn($components);
        $document->getComponents()->shouldBeCalled();

        $components->getSchemas()->willReturn([
            $modelName => $schema
        ]);
        $components->getSchemas()->shouldBeCalled();

        $modelGenerator->getNamespace($namespace)->willReturn($namespace . '\Model');
        $modelGenerator->getModelProperties($schema, $modelName)->willReturn([]);

        $templateService->render('model-hydrator', [
            'className'  => "TestHydrator",
            'namespace'  => $namespace . '\Hydrator',
            'modelName' => 'Test',
            'modelNamespace' => $namespace . '\Model',
            'properties' => []
        ])->willReturn(Argument::type('string'));
        $templateService->render('model-hydrator', [
            'className'  => "TestHydrator",
            'namespace'  => $namespace . '\Hydrator',
            'modelName' => 'Test',
            'modelNamespace' => $namespace . '\Model',
            'properties' => []
        ])->shouldBeCalled();

        $ignoreService->isIgnored(Argument::type('string'))->willReturn(false);
        $ignoreService->isIgnored(Argument::type('string'))->shouldBeCalled();

        $this->generateFromDocument($document, vfsStream::url('namespacePath'), $namespace);
    }

    public function it_can_get_hydrator_classes(
        Document $document,
        ModelGenerator $modelGenerator,
        Components $components,
        Schema $schema
    ) {
        $namespace = 'App';
        $modelName = 'Test';

        $modelGenerator->getNamespace($namespace)->willReturn($namespace . '\Model');

        $document->getComponents()->willReturn($components);
        $document->getComponents()->shouldBeCalled();

        $components->getSchemas()->willReturn([
            $modelName => $schema
        ]);
        $components->getSchemas()->shouldBeCalled();

        $this->getHydratorClasses($document, $namespace)->shouldBe([
            [
                'hydrator' => $namespace . '\Hydrator' . '\\' . $modelName . 'Hydrator::class',
                'model'    => $namespace . '\Model' . '\\' . $modelName . '::class'
            ]
        ]);
    }
}
