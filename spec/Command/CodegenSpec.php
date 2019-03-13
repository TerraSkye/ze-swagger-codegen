<?php

namespace spec\Swagger\Command;

use Prophecy\Argument;
use Swagger\Command\Codegen;
use PhpSpec\ObjectBehavior;
use Swagger\Composer;
use Swagger\Exception\CodegenException;
use Swagger\Ignore;
use Swagger\Parser;
use Swagger\Template;
use Swagger\Generator;
use Swagger\V30\Schema\Schema;
use Swagger\V30\Schema\Document;
use Swagger\V30\Schema\PathItem;
use Swagger\V30\Schema\Components;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CodegenSpec extends ObjectBehavior
{
    /**
     * @param  Parser $parser
     * @param  Template                       $template
     * @param  Generator\HandlerGenerator      $handlerGenerator
     * @param  Generator\ModelGenerator        $modelGenerator
     * @param  Generator\RoutesGenerator       $routesGenerator
     * @param  Generator\HydratorGenerator     $hydratorGenerator
     * @param  Generator\DependenciesGenerator $dependenciesGenerator
     * @param  Generator\ApiGenerator          $apiGenerator
     * @param  Ignore                         $ignoreService
     * @param EventDispatcherInterface $eventDispatcher
     * @param Composer $composer
     * @param InputDefinition $inputDefinition
     */
    public function let(
        Parser $parser,
        Template $template,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\ModelGenerator $modelGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Generator\HydratorGenerator $hydratorGenerator,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\ApiGenerator $apiGenerator,
        Ignore $ignoreService,
        HelperSet $helperSet,
        QuestionHelper $questionHelper,
        EventDispatcherInterface $eventDispatcher,
        Composer $composer,
        InputDefinition $inputDefinition
    ) {
        $this->beConstructedWith($parser, $template, $handlerGenerator, $modelGenerator, $routesGenerator, $hydratorGenerator, $dependenciesGenerator, $apiGenerator, $eventDispatcher, $composer, $inputDefinition);

        //Constructor executes protected configure method
        $this->getName()->shouldBe('codegen');
        $this->getDescription()->shouldBe('Generate code according to Swagger definition file.');

        $inputDefinition->addOption(Argument::type(InputOption::class))->shouldBeCalledTimes(4);
        $inputDefinition->getSynopsis(Argument::type('bool'))->willReturn('');

        $helperSet->get('question')->willReturn($questionHelper);

        $this->setHelperSet($helperSet);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Codegen::class);
    }

    public function it_cant_execute_the_command_because_of_missing_openapi_file(
        InputInterface $input,
        OutputInterface $output,
        EventDispatcherInterface $eventDispatcher
    ) {
        $eventDispatcher->addListener('swagger.codegen.generator.generated', Argument::type(\Closure::class))->shouldBeCalled();

        vfsStream::setup('projectRoot');
        $projectRoot = vfsStream::url('projectRoot');

        $input->getOption('project-root')->willReturn($projectRoot);

        $namespace = 'App';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $this->shouldThrow(new CodegenException('Could not find a openapi.json/openapi.yml in the project root'))->during('run', [$input, $output]);
    }

    public function it_cant_execute_because_of_invalid_project_root(
        InputInterface $input,
        OutputInterface $output,
        EventDispatcherInterface $eventDispatcher
        ) {
        $eventDispatcher->addListener('swagger.codegen.generator.generated', Argument::type(\Closure::class))->shouldBeCalled();

        $namespace = 'App';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $input->getOption('project-root')->willReturn('');

        $this->shouldThrow(new CodegenException('Invalid project root provided.'))->during('run', [$input, $output]);
    }

    public function it_can_execute(
        InputInterface $input,
        OutputInterface $output,
        Parser $parser,
        Document $document,
        PathItem $pathItem,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Components $components,
        Schema $schema,
        Generator\ModelGenerator $modelGenerator,
        Generator\HydratorGenerator $hydratorGenerator,
        HelperSet $helperSet,
        EventDispatcherInterface $eventDispatcher,
        Composer $composer
    ) {
        $eventDispatcher->addListener('swagger.codegen.generator.generated', Argument::type(\Closure::class))->shouldBeCalled();

        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/../data/openapi-composer'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $input->getOption('project-root')->willReturn($projectRoot);

        $configPath = $projectRoot . '/config/';

        $namespace = 'App';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $composerJson = json_decode(file_get_contents($projectRoot . '/composer.json'), true);

        $composer->getComposerAutoloaders($projectRoot)->willReturn($composerJson['autoload']['psr-4']);
        $composer->getComposerAutoloaders($projectRoot)->shouldBeCalled();

        $this->execute($namespace, $projectRoot, $configPath, $input, $output, $parser, $document, $pathItem, $dependenciesGenerator, $handlerGenerator, $routesGenerator, $components, $schema, $modelGenerator, $hydratorGenerator, $composer);

        $helperSet->get('question')->shouldNotBeCalled();

        $this->run($input, $output)->shouldBe(0);
        $this->shouldNotThrow(CodegenException::class)->during('run', [$input, $output]);
    }

    public function it_can_execute_without_autoload(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper,
        Parser $parser,
        Document $document,
        PathItem $pathItem,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Components $components,
        Schema $schema,
        Generator\ModelGenerator $modelGenerator,
        Generator\HydratorGenerator $hydratorGenerator,
        Composer $composer,
        EventDispatcherInterface $eventDispatcher,
        HelperSet $helperSet
    ) {
        $eventDispatcher->addListener('swagger.codegen.generator.generated', Argument::type(\Closure::class))->shouldBeCalled();

        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/../data/openapi-composer'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $input->getOption('project-root')->willReturn($projectRoot);

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

        $helperSet->get('question')->shouldBeCalled();

        $questionHelper->ask($input, $output, Argument::type(ConfirmationQuestion::class))->willReturn(true);
        $questionHelper->ask($input, $output, Argument::type(ConfirmationQuestion::class))->shouldBeCalled();

        $output->writeln('<info>Created folder for namespace: ' . $namespace . '</info>')->shouldBeCalled();

        $output->writeln('<info>Updated composer.json. Please run `composer dump-autoload` to refresh the autoloader.</info>')->shouldBeCalled();

        $composer->getComposerAutoloaders($projectRoot)->willReturn([]);
        $composer->getComposerAutoloaders($projectRoot)->shouldBeCalled();

        $composer->getComposerJsonPath($projectRoot)->willReturn($projectRoot . '/composer.json');
        $composer->getComposerJsonPath($projectRoot)->shouldBeCalled();

        $this->execute($namespace, $projectRoot, $configPath, $input, $output, $parser, $document, $pathItem, $dependenciesGenerator, $handlerGenerator, $routesGenerator, $components, $schema, $modelGenerator, $hydratorGenerator, $composer);

        $this->run($input, $output)->shouldBe(0);
    }

    public function it_can_execute_without_autoload_and_throw_exception(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper,
        Parser $parser,
        Document $document,
        PathItem $pathItem,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Components $components,
        Schema $schema,
        Generator\ModelGenerator $modelGenerator,
        Generator\HydratorGenerator $hydratorGenerator,
        Composer $composer,
        EventDispatcherInterface $eventDispatcher,
        HelperSet $helperSet
    ) {
        $eventDispatcher->addListener('swagger.codegen.generator.generated', Argument::type(\Closure::class))->shouldBeCalled();

        $vfsProjectRoot = vfsStream::setup('projectRoot');

        vfsStream::copyFromFileSystem(realpath(__DIR__ . '/../data/openapi-composer'), $vfsProjectRoot);
        $projectRoot = vfsStream::url('projectRoot');

        $input->getOption('project-root')->willReturn($projectRoot);

        $configPath = $projectRoot . '/config/';

        $namespace = 'Test2';

        $input->getOption('namespace')->willReturn($namespace);
        $input->getOption('client')->willReturn(false);
        $input->getOption('routes-from-config')->willReturn(false);

        $input->bind(Argument::type(InputDefinition::class))->willReturn();
        $input->isInteractive()->willReturn(true);
        $input->hasArgument('command')->willReturn(false);
        $input->validate()->willReturn(true);

        $composer->getComposerAutoloaders($projectRoot)->willReturn([]);
        $composer->getComposerAutoloaders($projectRoot)->shouldBeCalled();

        $output->writeln(sprintf('<error>Unable to match %s to an autoloadable PSR-4 namespace. </error>', $namespace))->shouldBeCalled();

        $helperSet->get('question')->shouldBeCalled();

        $questionHelper->ask($input, $output, Argument::type(ConfirmationQuestion::class))->willReturn(false);
        $questionHelper->ask($input, $output, Argument::type(ConfirmationQuestion::class))->shouldBeCalled();

        $this->shouldThrow(new CodegenException(sprintf('Unable to match %s to an autoloadable PSR-4 namespace', $namespace)))->during('run', [$input, $output]);
    }

    protected function execute(
        string $namespace,
        string $projectRoot,
        string $configPath,
        InputInterface $input,
        OutputInterface $output,
        Parser $parser,
        Document $document,
        PathItem $pathItem,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Components $components,
        Schema $schema,
        Generator\ModelGenerator $modelGenerator,
        Generator\HydratorGenerator $hydratorGenerator,
        Composer $composer
    ) {
        $parser->parseFile(Argument::type('string'))->willReturn($document);
        $parser->parseFile(Argument::type('string'))->shouldBeCalled();

        $namespacePath = $projectRoot . '/' . $this->getNamespacePath($namespace, $input, $output)->getWrappedObject();

        $handlerGenerator->generateFromDocument($document, $namespacePath, $namespace)->willReturn('TestHandler');
        $handlerGenerator->generateFromDocument($document, $namespacePath, $namespace)->shouldBeCalled();

        $routesGenerator->generateFromDocument($document, $namespace, $configPath, false)->willReturn(true);
        $routesGenerator->generateFromDocument($document, $namespace, $configPath, false)->shouldBeCalled();

        $output->writeln('<info>Generated routes</info>')->shouldBeCalled();

        $modelGenerator->generateFromDocument($document, $namespacePath, $namespace)->willReturn('Item');
        $modelGenerator->generateFromDocument($document, $namespacePath, $namespace)->shouldBeCalled();

        $hydratorGenerator->generateFromDocument($document, $namespacePath, $namespace)->willReturn('ItemHydrator');
        $hydratorGenerator->generateFromDocument($document, $namespacePath, $namespace)->shouldBeCalled();

        $dependenciesGenerator->generateFromDocument($document, $namespace, $configPath)->willReturn(true);
        $dependenciesGenerator->generateFromDocument($document, $namespace, $configPath)->shouldBeCalled();

        $output->writeln('<info>Generated dependencies config</info>')->shouldBeCalled();
    }
}
