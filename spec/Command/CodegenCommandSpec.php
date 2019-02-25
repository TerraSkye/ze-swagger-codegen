<?php

namespace spec\Swagger\Command;

use Prophecy\Argument;
use Swagger\Command\CodegenCommand;
use PhpSpec\ObjectBehavior;
use Swagger\Exception\CodegenException;
use Swagger\Ignore;
use Swagger\Template;
use Swagger\Generator;
use Swagger\V30\Hydrator\DocumentHydrator;
use Swagger\V30\Schema\Schema;
use Swagger\V30\Schema\Document;
use Swagger\V30\Schema\PathItem;
use Swagger\V30\Schema\Components;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use org\bovigo\vfs\vfsStream;

class CodegenCommandSpec extends ObjectBehavior
{
    /**
     * @param  DocumentHydrator               $documentHydrator
     * @param  Template                       $template
     * @param  Generator\HandlerGenerator      $handlerGenerator
     * @param  Generator\ModelGenerator        $modelGenerator
     * @param  Generator\RoutesGenerator       $routesGenerator
     * @param  Generator\HydratorGenerator     $hydratorGenerator
     * @param  Generator\DependenciesGenerator $dependenciesGenerator
     * @param  Generator\ApiGenerator          $apiGenerator
     * @param  Ignore                         $ignoreService
     */
    public function let(
        DocumentHydrator $documentHydrator,
        Template $template,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\ModelGenerator $modelGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Generator\HydratorGenerator $hydratorGenerator,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\ApiGenerator $apiGenerator,
        Ignore $ignoreService,
        HelperSet $helperSet,
        QuestionHelper $questionHelper
    ) {
        $this->beConstructedWith($documentHydrator, $template, $handlerGenerator, $modelGenerator, $routesGenerator, $hydratorGenerator, $dependenciesGenerator, $apiGenerator);

        //Constructor executes protected configure method
        $this->getName()->shouldBe('codegen');
        $this->getDescription()->shouldBe('Generate code according to Swagger definition file.');

        $helperSet->get('question')->willReturn($questionHelper);

        $this->setHelperSet($helperSet);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CodegenCommand::class);
    }

    public function it_cant_execute_the_command_because_of_missing_openapi_file(
        InputInterface $input,
        OutputInterface $output
    ) {
        $namespace = 'App';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $this->shouldThrow(CodegenException::class)->during('run', [$input, $output]);
    }

    public function it_cant_execute_the_command_because_of_missing_composer_json(
        InputInterface $input,
        OutputInterface $output
    ) {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/../data/only-openapi'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $this->setProjectRoot($projectRoot);

        $namespace = 'App';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $this->shouldThrow(CodegenException::class)->during('run', [$input, $output]);
    }

    public function it_cant_execute_the_command_because_of_invalid_composer_json(
        InputInterface $input,
        OutputInterface $output
    ) {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/../data/only-openapi'), $vfsProjectRoot);

        $projectRoot = vfsStream::url('projectRoot');

        vfsStream::newFile('composer.json')->at($vfsProjectRoot);

        $this->setProjectRoot($projectRoot);

        $namespace = 'App';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $this->shouldThrow(CodegenException::class)->during('run', [$input, $output]);
    }

    public function it_cant_execute_the_command_because_of_missing_psr4_autoloader(
        InputInterface $input,
        OutputInterface $output
    ) {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/../data/only-openapi'), $vfsProjectRoot);

        $projectRoot = vfsStream::url('projectRoot');

        vfsStream::newFile('composer.json')->withContent('{"autoload":{}}')->at($vfsProjectRoot);

        $this->setProjectRoot($projectRoot);

        $namespace = 'App';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $this->shouldThrow(CodegenException::class)->during('run', [$input, $output]);
    }

    public function it_cant_execute_the_command_because_of_invalid_psr4_autoloader(
        InputInterface $input,
        OutputInterface $output
    ) {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/../data/only-openapi'), $vfsProjectRoot);

        $projectRoot = vfsStream::url('projectRoot');

        vfsStream::newFile('composer.json')->withContent('{"autoload":{"psr-4":"invalid"}}')->at($vfsProjectRoot);

        $this->setProjectRoot($projectRoot);

        $namespace = 'App';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $this->shouldThrow(CodegenException::class)->during('run', [$input, $output]);
    }

    public function it_can_execute(
        InputInterface $input,
        OutputInterface $output,
        DocumentHydrator $documentHydrator,
        Document $document,
        PathItem $pathItem,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Components $components,
        Schema $schema,
        Generator\ModelGenerator $modelGenerator,
        Generator\HydratorGenerator $hydratorGenerator,
        HelperSet $helperSet
    ) {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/../data/openapi-composer'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $this->setProjectRoot($projectRoot);

        $configPath = $projectRoot . '/config/';

        $namespace = 'App';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $this->execute($namespace, $projectRoot, $configPath, $input, $output, $documentHydrator, $document, $pathItem, $dependenciesGenerator, $handlerGenerator, $routesGenerator, $components, $schema, $modelGenerator, $hydratorGenerator);

        $helperSet->get('question')->shouldNotBeCalled();

        $this->run($input, $output)->shouldBe(0);
    }

    public function it_can_execute_with_autoload_from_vendor(
        InputInterface $input,
        OutputInterface $output,
        DocumentHydrator $documentHydrator,
        Document $document,
        PathItem $pathItem,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Components $components,
        Schema $schema,
        Generator\ModelGenerator $modelGenerator,
        Generator\HydratorGenerator $hydratorGenerator,
        HelperSet $helperSet
    ) {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/../data/openapi-composer-vendor'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $this->setProjectRoot($projectRoot);

        $configPath = $projectRoot . '/config/';

        $namespace = 'App';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $this->execute($namespace, $projectRoot, $configPath, $input, $output, $documentHydrator, $document, $pathItem, $dependenciesGenerator, $handlerGenerator, $routesGenerator, $components, $schema, $modelGenerator, $hydratorGenerator);

        $helperSet->get('question')->shouldNotBeCalled();

        $this->run($input, $output)->shouldBe(0);
    }

    public function it_can_execute_without_autoload(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper,
        DocumentHydrator $documentHydrator,
        Document $document,
        PathItem $pathItem,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Components $components,
        Schema $schema,
        Generator\ModelGenerator $modelGenerator,
        Generator\HydratorGenerator $hydratorGenerator
    ) {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/../data/openapi-composer'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $this->setProjectRoot($projectRoot);

        $configPath = $projectRoot . '/config/';

        $namespace = 'Test2';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $output->writeln(sprintf('<error>Unable to match %s to an autoloadable PSR-4 namespace. </error>', $namespace))->shouldBeCalled();

        $questionHelper->ask($input, $output, Argument::type(ConfirmationQuestion::class))->willReturn(true);
        $questionHelper->ask($input, $output, Argument::type(ConfirmationQuestion::class))->shouldBeCalled();

        $output->writeln('<info>Created folder for namespace: ' . $namespace . '</info>')->shouldBeCalled();

        $output->writeln('<info>Updated composer.json. Please run `composer dump-autoload` to refresh the autoloader.</info>')->shouldBeCalled();

        $this->execute($namespace, $projectRoot, $configPath, $input, $output, $documentHydrator, $document, $pathItem, $dependenciesGenerator, $handlerGenerator, $routesGenerator, $components, $schema, $modelGenerator, $hydratorGenerator);

        $this->run($input, $output)->shouldBe(0);
    }

    public function it_can_execute_without_autoload_and_throw_exception(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper,
        DocumentHydrator $documentHydrator,
        Document $document,
        PathItem $pathItem,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Components $components,
        Schema $schema,
        Generator\ModelGenerator $modelGenerator,
        Generator\HydratorGenerator $hydratorGenerator
    ) {
        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/../data/openapi-composer'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $this->setProjectRoot($projectRoot);

        $configPath = $projectRoot . '/config/';

        $namespace = 'Test2';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $output->writeln(sprintf('<error>Unable to match %s to an autoloadable PSR-4 namespace. </error>', $namespace))->shouldBeCalled();

        $questionHelper->ask($input, $output, Argument::type(ConfirmationQuestion::class))->willReturn(false);
        $questionHelper->ask($input, $output, Argument::type(ConfirmationQuestion::class))->shouldBeCalled();

        $this->shouldThrow(CodegenException::class)->during('run', [$input, $output]);
    }

    protected function execute(
        string $namespace,
        string $projectRoot,
        string $configPath,
        InputInterface $input,
        OutputInterface $output,
        DocumentHydrator $documentHydrator,
        Document $document,
        PathItem $pathItem,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Components $components,
        Schema $schema,
        Generator\ModelGenerator $modelGenerator,
        Generator\HydratorGenerator $hydratorGenerator
    ) {
        $documentHydrator->hydrate(Argument::type('array'), Argument::type(Document::class))->willReturn($document);
        $documentHydrator->hydrate(Argument::type('array'), Argument::type(Document::class))->shouldBeCalled();

        $path = '/path';
        $document->getPaths()->willReturn([
            $path => $pathItem
        ]);
        $document->getPaths()->shouldBeCalled();

        $handlerGenerator->generateFromPathItem($pathItem, $path, $projectRoot . '/' . ltrim($this->getNamespacePathPublic($namespace, $input, $output)->getWrappedObject(), 'src/'), $namespace)->willReturn('TestHandler');
        $handlerGenerator->generateFromPathItem($pathItem, $path, $projectRoot . '/' . ltrim($this->getNamespacePathPublic($namespace, $input, $output)->getWrappedObject(), 'src/'), $namespace)->shouldBeCalled();

        $output->writeln('<info>Handler generated: TestHandler</info>')->shouldBeCalled();

        $routesGenerator->generateFromDocument($document, $namespace, $configPath, false)->willReturn(true);

        $output->writeln('<info>Generated routes</info>')->shouldBeCalled();

        $document->getComponents()->willReturn($components);

        $components->getSchemas()->willReturn([
            'Item' => $schema
        ]);
        $components->getSchemas()->shouldBeCalled();

        $modelGenerator->generateFromSchema($schema, 'Item', $projectRoot . '/' . ltrim($this->getNamespacePathPublic($namespace, $input, $output)->getWrappedObject(), 'src/'), $namespace)->willReturn('Item');

        $output->writeln('<info>Model generated: Item</info>')->shouldBeCalled();

        $hydratorGenerator->generateFromSchema($schema, 'Item', $projectRoot . '/' . ltrim($this->getNamespacePathPublic($namespace, $input, $output)->getWrappedObject(), 'src/'), $namespace)->willReturn('ItemHydrator');

        $output->writeln('<info>Hydrator generated: ItemHydrator</info>')->shouldBeCalled();

        $dependenciesGenerator->generateFromDocument($document, $namespace, $configPath)->willReturn(true);

        $output->writeln('<info>Generated dependencies config</info>')->shouldBeCalled();
    }
}
