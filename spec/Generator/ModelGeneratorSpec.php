<?php

namespace spec\Swagger\Generator;

use Prophecy\Argument;

use Swagger\Generator\ModelGenerator;
use PhpSpec\ObjectBehavior;
use Swagger\Ignore;
use Swagger\Template;
use Swagger\V30\Schema\Reference;
use Swagger\V30\Schema\Components;
use Swagger\V30\Schema\Schema;
use org\bovigo\vfs\vfsStream;
use Swagger\V30\Schema\Document;

class ModelGeneratorSpec extends ObjectBehavior
{
    /**
     * @var string
     */
    protected $namespace = 'App';

    /**
     * @param  Template         $templateService
     * @param  Ignore           $ignoreService
     */
    public function let(
        Template $templateService,
        Ignore $ignoreService
    ) {
        $this->beConstructedWith($templateService, $ignoreService);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ModelGenerator::class);
    }

    public function it_can_get_the_model_namespace()
    {
        $this->getNamespace($this->namespace)->shouldBeString();
        $this->getNamespace($this->namespace)->shouldBe($this->namespace . '\Model');
    }

    /**
     * @param  Document         $document
     * @param  Ignore           $ignoreService
     * @param  Template         $templateService
     */
    public function it_can_generate_from_document(
        Document $document,
        Ignore $ignoreService,
        Template $templateService,
        Components $components,
        Schema $schema,
        Schema $property
    ) {
        $modelName = 'Test';

        vfsStream::setup('namespacePath');

        $document->getComponents()->willReturn($components);
        $document->getComponents()->shouldBeCalled();

        $components->getSchemas()->willReturn([
            $modelName => $schema
        ]);
        $components->getSchemas()->shouldBeCalled();

        $ignoreService->isIgnored(Argument::type('string'))->willReturn(false);
        $ignoreService->isIgnored(Argument::type('string'))->shouldBeCalled();

        $schema->isObject()->willReturn(true);
        $schema->getProperties()->willReturn([
            'name' => $property
        ]);

        $schema->isRequired('name')->willReturn(true);

        $property->getPhpType()->willReturn('string');

        $templateService->render('model', [
            'className'  => $modelName,
            'namespace'  => $this->namespace . '\Model',
            'properties' => [["name" => "name", "typeHint" => "string", "getter" => "getName", "setter" => "setName", "validators" => ["Zend\Validator\NotEmpty"]]],
            'hydrator' => $this->namespace . '\Hydrator\TestHydrator'
        ])->willReturn(Argument::type('string'));

        $this->generateFromDocument($document, vfsStream::url('namespacePath'), $this->namespace);
        $this->generateFromDocument($document, vfsStream::url('namespacePath'), $this->namespace);
    }

    public function it_can_get_model_classes(
        Document $document,
        Components $components,
        Schema $schema
    ) {
        $modelName = 'Test';

        $document->getComponents()->willReturn($components);
        $document->getComponents()->shouldBeCalled();

        $components->getSchemas()->willReturn([
            $modelName => $schema
        ]);
        $components->getSchemas()->shouldBeCalled();

        $this->getModelClasses($document, $this->namespace)->shouldBeArray();
        $this->getModelClasses($document, $this->namespace)->shouldBe([
            $this->namespace . '\Model\Test::class'
        ]);
    }

    public function it_cant_generate_from_schema_because_of_ignore(
        Schema $schema,
        Ignore $ignoreService
    ) {
        vfsStream::setup('namespacePath');

        $ignoreService->isIgnored(Argument::type('string'))->willReturn(true);

        $this->generateFromSchema($schema, 'Test', vfsStream::url('namespacePath'), $this->namespace)->shouldBe(null);
    }

    public function it_can_get_model_properties_from_array(
        Schema $schema,
        Reference $reference
    ) {
        $fieldName = 'items';

        $schema->isObject()->willReturn(false);
        $schema->isArray()->willReturn(true);

        $schema->getItems()->willReturn($reference);

        $schema->isRequired($fieldName)->willReturn(true);

        $reference->getRef()->willReturn('#/components/schemas/Item');

        $this->getModelProperties($schema, 'Item')->shouldBeArray();
        $this->getModelProperties($schema, 'Item')->shouldBe([
            [
                "name" => "items",
                "typeHint" => "array",
                "docTypeHint" => 'Item[]',
                'defaultValue' => '[]',
                "getter" => "getItems",
                "setter" => "setItems",
                "validators" => ["Zend\Validator\NotEmpty"]]
        ]);
    }
}
