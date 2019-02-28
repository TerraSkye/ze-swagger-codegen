<?php

declare(strict_types=1);

namespace Swagger\Command;

use Swagger\Composer;
use Swagger\Exception\CodegenException;
use Swagger\Generator\HandlerGenerator;
use Swagger\Generator\ModelGenerator;
use Swagger\Generator\RoutesGenerator;
use Swagger\Generator\HydratorGenerator;
use Swagger\Generator\DependenciesGenerator;
use Swagger\Generator\ApiGenerator;
use Swagger\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;
use Swagger\Template;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Codegen extends Command
{
    /**
     * @var Template
     */
    protected $templateService;

    /**
     * @var HandlerGenerator
     */
    protected $handlerGenerator;

    /**
     * @var ModelGenerator
     */
    protected $modelGenerator;

    /**
     * @var RoutesGenerator
     */
    protected $routesGenerator;

    /**
     * @var HydratorGenerator
     */
    protected $hydratorGenerator;

    /**
     * @var DependenciesGenerator
     */
    protected $dependenciesGenerator;

    /**
     * @var ApiGenerator
     */
    protected $apiGenerator;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @param Parser $parser
     * @param Template $templateService
     * @param HandlerGenerator $handlerGenerator
     * @param ModelGenerator $modelGenerator
     * @param RoutesGenerator $routesGenerator
     * @param HydratorGenerator $hydratorGenerator
     * @param DependenciesGenerator $dependenciesGenerator
     * @param ApiGenerator $apiGenerator
     * @param EventDispatcherInterface $eventDispatcher
     * @param Composer $composer
     * @param InputDefinition $definition
     */
    public function __construct(
        Parser $parser,
        Template $templateService,
        HandlerGenerator $handlerGenerator,
        ModelGenerator $modelGenerator,
        RoutesGenerator $routesGenerator,
        HydratorGenerator $hydratorGenerator,
        DependenciesGenerator $dependenciesGenerator,
        ApiGenerator $apiGenerator,
        EventDispatcherInterface $eventDispatcher,
        Composer $composer,
        InputDefinition $definition
    ) {
        $this->parser = $parser;
        $this->templateService = $templateService;
        $this->handlerGenerator = $handlerGenerator;
        $this->modelGenerator = $modelGenerator;
        $this->routesGenerator = $routesGenerator;
        $this->hydratorGenerator = $hydratorGenerator;
        $this->dependenciesGenerator = $dependenciesGenerator;
        $this->apiGenerator = $apiGenerator;
        $this->eventDispatcher = $eventDispatcher;
        $this->composer = $composer;

        $this->setDefinition($definition);

        $this->configure();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('codegen');
        $this->setDescription('Generate code according to Swagger definition file.');
        $this->addOption('namespace', 'ns', InputOption::VALUE_OPTIONAL, 'The namespace to generate the Swagger code to.', 'App');
        $this->addOption('client', null, InputOption::VALUE_NONE, 'Generate a REST client instead of the server.');
        $this->addOption('routes-from-config', null, InputOption::VALUE_NONE, 'Generate routes in config instead of programmatic.');
        $this->addOption('project-root', null, InputOption::VALUE_OPTIONAL, 'The project root. Defaults to current working directory', getcwd());
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $this->eventDispatcher->addListener('swagger.codegen.generator.generated', function (GenericEvent $event) use ($output) {
            $subject = $event->getSubject();
            $output->writeln(sprintf('<info>%s generated: %s</info>', $subject['generator'], $subject['name']));
        });

        $namespace = $input->getOption('namespace');
        $generateClient = $input->getOption('client');
        $routesFromConfig = $input->getOption('routes-from-config');
        $projectRoot = realpath($input->getOption('project-root'));

        if (!$projectRoot) {
            throw new CodegenException('Invalid project root provided.');
        }

        $swaggerFile = $projectRoot . DIRECTORY_SEPARATOR . 'openapi.json';

        if (!is_file($swaggerFile)) {
            $swaggerFile = $projectRoot . DIRECTORY_SEPARATOR . 'openapi.yml';

            if (!is_file($swaggerFile)) {
                throw CodegenException::missingSwaggerJson();
            }

            if (!function_exists('yaml_parse')) {
                throw CodegenException::missingYamlExtension();
            }
        }

        $namespacePath = $projectRoot . DIRECTORY_SEPARATOR . $this->getNamespacePath($namespace, $input, $output);

        $document = $this->parser->parseFile($swaggerFile);

        $this->handlerGenerator->generateFromDocument($document, $namespacePath, $namespace);

        $configPath = $projectRoot . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;

        $generated = $this->routesGenerator->generateFromDocument($document, $namespace, $configPath, $routesFromConfig);

        if ($generated) {
            $output->writeln('<info>Generated routes</info>');
        }

        $this->modelGenerator->generateFromDocument($document, $namespacePath, $namespace);

        $this->hydratorGenerator->generateFromDocument($document, $namespacePath, $namespace);

        $generated = $this->dependenciesGenerator->generateFromDocument($document, $namespace, $configPath);

        if ($generated) {
            $output->writeln('<info>Generated dependencies config</info>');
        }

        return 0;
    }

    /**
     * @param  string $namespace
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     *
     * @throws CodegenException
     */
    public function getNamespacePath(string $namespace, InputInterface $input, OutputInterface $output): string
    {
        $projectRoot = realpath($input->getOption('project-root'));

        $autoloaders = $this->composer->getComposerAutoloaders($projectRoot);

        foreach ($autoloaders as $registeredNamespace => $path) {
            if (0 === strpos($registeredNamespace, $namespace)) {
                $path = ltrim($path,  $projectRoot);

                $path = trim(
                    str_replace(
                        ['/', '\\'],
                        DIRECTORY_SEPARATOR,
                        $path
                    ),
                    DIRECTORY_SEPARATOR
                );
                return $path;
            }
        }

        $helper = $this->getHelper('question');

        $output->writeln(sprintf('<error>Unable to match %s to an autoloadable PSR-4 namespace. </error>', $namespace));

        $question = new ConfirmationQuestion('Do you want to add the new namespace to composer.json?', false);

        if ($helper->ask($input, $output, $question)) {
            $namespacePath = 'src' . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

            if (!is_dir($projectRoot . DIRECTORY_SEPARATOR . $namespacePath)) {
                mkdir($projectRoot . DIRECTORY_SEPARATOR . $namespacePath, 0755, true);
                $output->writeln('<info>Created folder for namespace: ' . $namespace . '</info>');
            }

            $composerPath = $this->composer->getComposerJsonPath($projectRoot);

            $composer = json_decode(file_get_contents($composerPath), true);

            $composer['autoload']['psr-4'][$namespace . '\\'] = $namespacePath;

            $this->writeFile($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            $output->writeln('<info>Updated composer.json. Please run `composer dump-autoload` to refresh the autoloader.</info>');

            return trim(
                str_replace(
                    ['/', '\\'],
                    DIRECTORY_SEPARATOR,
                    $namespacePath
                ),
                DIRECTORY_SEPARATOR
            );
        }

        throw CodegenException::autoloaderNotFound($namespace);
    }

    /**
     * @param  string $path
     * @param  string $contents
     */
    protected function writeFile(string $path, string $contents)
    {
        $folder = str_replace(basename($path), '', $path);

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        file_put_contents($path, $contents);
    }
}
