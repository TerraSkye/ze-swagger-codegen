<?php

namespace spec\Swagger\Generator;

use Swagger\Generator\HydratorGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use org\bovigo\vfs\vfsStream;
use Swagger\Template;
use Swagger\V30\Schema\Document;
use Swagger\V30\Schema\Schema;
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
        Ignore $ignoreService,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->beConstructedWith($templateService, $modelGenerator, $ignoreService, $eventDispatcher);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(HydratorGenerator::class);
    }

    public function it_can_generate_from_schema(
        Schema $schema,
        ModelGenerator $modelGenerator,
        Template $templateService,
        Ignore $ignoreService,
        EventDispatcherInterface $eventDispatcher
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

        $eventDispatcher->dispatch('swagger.codegen.generator.generated', Argument::type(GenericEvent::class))->shouldBeCalled();

        $ignoreService->isIgnored(Argument::type('string'))->willReturn(false);
        $ignoreService->isIgnored(Argument::type('string'))->shouldBeCalled();

        $this->generateFromSchema($schema, 'test', vfsStream::url('namespacePath'), $namespace)->shouldBeString();
    }

    public function it_cant_generate_from_schema_because_of_ignore(
        Schema $schema,
        Ignore $ignoreService,
        ModelGenerator $modelGenerator
    ) {
        $namespace = 'App';

        $modelGenerator->getNamespace($namespace)->willReturn($namespace . '\Model');

        $ignoreService->isIgnored(Argument::type('string'))->willReturn(true);
        $ignoreService->isIgnored(Argument::type('string'))->shouldBeCalled();

        $this->generateFromSchema($schema, 'test', vfsStream::url('namespacePath'), $namespace)->shouldBe(null);
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
        Ignore $ignoreService,
        EventDispatcherInterface $eventDispatcher
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

        $eventDispatcher->dispatch('swagger.codegen.generator.generated', Argument::type(GenericEvent::class))->shouldBeCalled();

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
