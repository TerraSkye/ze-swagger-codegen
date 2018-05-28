<?php

namespace spec\Swagger\Generator;

use Swagger\Template;
use Swagger\Generator\ModelGenerator;
use Swagger\Generator\HydratorGenerator;
use Swagger\Generator\DependenciesGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Swagger\V30\Schema\Document;
use org\bovigo\vfs\vfsStream;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class DependenciesGeneratorSpec extends ObjectBehavior
{
    public function let(
        Template $templateService,
        HydratorGenerator $hydratorGenerator,
        ModelGenerator $modelGenerator
    ) {
        $this->beConstructedWith($templateService, $hydratorGenerator, $modelGenerator);
    }

    // phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function it_is_initializable()
    {
        $this->shouldHaveType(DependenciesGenerator::class);
    }

    public function it_can_generate_from_document(
        Document $document,
        Template $templateService,
        HydratorGenerator $hydratorGenerator,
        ModelGenerator $modelGenerator
    ) {
        vfsStream::setup('configDir');

        $templateService->render('dependencies', [
            'models'    => Argument::type('array'),
            'hydrators' => Argument::type('array')
        ])->willReturn(Argument::type('string'));
        $templateService->render('dependencies', [
            'models'    => Argument::type('array'),
            'hydrators' => Argument::type('array')
        ])->shouldBeCalled();

        $hydratorGenerator->getHydratorClasses($document, Argument::type('string'))->willReturn(Argument::type('array'));
        $hydratorGenerator->getHydratorClasses($document, Argument::type('string'))->shouldBeCalled();

        $modelGenerator->getModelClasses($document, Argument::type('string'))->willReturn(Argument::type('array'));
        $modelGenerator->getModelClasses($document, Argument::type('string'))->shouldBeCalled();

        $this->generateFromDocument($document, Argument::type('string'), vfsStream::url('configDir') . DIRECTORY_SEPARATOR);
    }
}
