<?php

namespace spec\Swagger\Generator;

use Swagger\Ignore;
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
        ModelGenerator $modelGenerator,
        Ignore $ignoreService
    ) {
        $this->beConstructedWith($templateService, $hydratorGenerator, $modelGenerator, $ignoreService);
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
        ModelGenerator $modelGenerator,
        Ignore $ignoreService
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

        $configFolder = vfsStream::url('configDir') . DIRECTORY_SEPARATOR . 'autoload';

        $dependencyConfigPath = $configFolder . DIRECTORY_SEPARATOR . 'swagger.dependencies.global.php';

        $ignoreService->isIgnored($dependencyConfigPath)->willReturn(false);
        $ignoreService->isIgnored($dependencyConfigPath)->shouldBeCalled();

        $this->generateFromDocument($document, Argument::type('string'), vfsStream::url('configDir') . DIRECTORY_SEPARATOR)->shouldBeBool();
        $this->generateFromDocument($document, Argument::type('string'), vfsStream::url('configDir') . DIRECTORY_SEPARATOR)->shouldBe(true);

        // $this->fileExists($dependencyConfigPath)->shouldBe(true);
        // $this->folderExists($configFolder)->shouldBe(true);
        // $this->assertFolderPermissions($configFolder)->shouldBe(true);
    }

    public function it_cant_generate_from_document_because_of_ignore(
        Document $document,
        Ignore $ignoreService,
        Template $templateService
    ) {
        vfsStream::setup('configDir');

        $configFolder = vfsStream::url('configDir') . DIRECTORY_SEPARATOR . 'autoload';

        $dependencyConfigPath = $configFolder . DIRECTORY_SEPARATOR . 'swagger.dependencies.global.php';

        $ignoreService->isIgnored($dependencyConfigPath)->willReturn(true);
        $ignoreService->isIgnored($dependencyConfigPath)->shouldBeCalled();

        $templateService->render('dependencies', [
            'models'    => Argument::type('array'),
            'hydrators' => Argument::type('array')
        ])->shouldNotBeCalled();

        $this->generateFromDocument($document, Argument::type('string'), vfsStream::url('configDir') . DIRECTORY_SEPARATOR)->shouldBeBool();
        $this->generateFromDocument($document, Argument::type('string'), vfsStream::url('configDir') . DIRECTORY_SEPARATOR)->shouldBe(false);
    }
}
