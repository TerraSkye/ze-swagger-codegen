<?php

declare(strict_types=1);

namespace Swagger\Command;

use Swagger\Exception\CodegenException;
use Swagger\Generator\HandlerGenerator;
use Swagger\Generator\ModelGenerator;
use Swagger\Generator\RoutesGenerator;
use Swagger\Generator\HydratorGenerator;
use Swagger\Generator\DependenciesGenerator;
use Swagger\Generator\ApiGenerator;
use Swagger\V30\Object\Reference;
use Swagger\V30\Object\Schema;
use Swagger\V30\Object\PathItem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Swagger\V30\Hydrator\DocumentHydrator;
use Swagger\V30\Object\Document as V30Document;
use Swagger\Template;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class Codegen extends Command
{
    /**
     * @var DocumentHydrator
     */
    protected $documentHydrator;

    /**
     * @var Template
     */
    protected $templateService;

    /**
     * @var string
     */
    protected $projectRoot;

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
     * @param DocumentHydrator $documentHydrator
     * @param Template $templateService
     * @param HandlerGenerator $handlerGenerator
     * @param ModelGenerator $modelGenerator
     * @param RoutesGenerator $routesGenerator
     * @param HydratorGenerator $hydratorGenerator
     * @param DependenciesGenerator $dependenciesGenerator
     * @param ApiGenerator $apiGenerator
     */
    public function __construct(
        DocumentHydrator $documentHydrator,
        Template $templateService,
        HandlerGenerator $handlerGenerator,
        ModelGenerator $modelGenerator,
        RoutesGenerator $routesGenerator,
        HydratorGenerator $hydratorGenerator,
        DependenciesGenerator $dependenciesGenerator,
        ApiGenerator $apiGenerator
    ) {
        $this->documentHydrator = $documentHydrator;
        $this->templateService = $templateService;
        $this->handlerGenerator = $handlerGenerator;
        $this->modelGenerator = $modelGenerator;
        $this->routesGenerator = $routesGenerator;
        $this->hydratorGenerator = $hydratorGenerator;
        $this->dependenciesGenerator = $dependenciesGenerator;
        $this->apiGenerator = $apiGenerator;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('codegen')
            ->setDescription('Generate code according to Swagger definition file.')
            ->addOption('namespace', 'ns', InputOption::VALUE_OPTIONAL, 'The namespace to generate the Swagger code to.', 'App')
            ->addOption('client', null, InputOption::VALUE_NONE, 'Generate a REST client instead of the server.');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $namespace = $input->getOption('namespace');
        $generateClient = $input->getOption('client');

        $this->projectRoot = getcwd();
        $swaggerFile = $this->projectRoot . DIRECTORY_SEPARATOR . 'openapi.json';

        $namespacePath = $this->projectRoot . DIRECTORY_SEPARATOR . $this->getNamespacePath($namespace, $input, $output);

        if (!is_file($swaggerFile)) {
            throw CodegenException::missingSwaggerJson();
        }

        $document = $this->parseFile($swaggerFile);

        if ($generateClient) {
            foreach ($document->getComponents()->getSchemas() as $name => $schema) {
                /** @var Schema|Reference $schema **/

                $generatedModel = $this->modelGenerator->generateFromSchema($schema, $name, $namespacePath, $namespace);

                $output->writeln(sprintf('<info>Model generated: %s</info>', $generatedModel));

                $generateHydrator = $this->hydratorGenerator->generateFromSchema($schema, $name, $namespacePath, $namespace);

                $output->writeln(sprintf('<info>Hydrator generated: %s</info>', $generateHydrator));
            }

            $this->apiGenerator->generateFromDocument($document, $namespacePath, $namespace);

            return 0;
        }

        foreach ($document->getPaths() as $path => $pathItem) {
            /** @var PathItem $pathItem **/

            $generatedHandler = $this->handlerGenerator->generateFromPathItem($pathItem, $path, $namespacePath, $namespace);

            if (!is_null($generatedHandler)) {
                $output->writeln(sprintf('<info>Handler generated: %s</info>', $generatedHandler));
            }
        }

        $configPath = $this->projectRoot . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;

        $generated = $this->routesGenerator->generateFromDocument($document, $namespace, $configPath);

        if ($generated) {
            $output->writeln('<info>Generated routes</info>');
        }

        foreach ($document->getComponents()->getSchemas() as $name => $schema) {
            /** @var Schema|Reference $schema **/

            $generatedModel = $this->modelGenerator->generateFromSchema($schema, $name, $namespacePath, $namespace);

            if (!is_null($generatedModel)) {
                $output->writeln(sprintf('<info>Model generated: %s</info>', $generatedModel));
            }

            $generatedHydrator = $this->hydratorGenerator->generateFromSchema($schema, $name, $namespacePath, $namespace);

            if (!is_null($generatedHydrator)) {
                $output->writeln(sprintf('<info>Hydrator generated: %s</info>', $generatedHydrator));
            }
        }

        $generated = $this->dependenciesGenerator->generateFromDocument($document, $namespace, $configPath);

        if ($generated) {
            $output->writeln('<info>Generated dependencies config</info>');
        }

        return 0;
    }

    /**
     * @param  string $file
     *
     * @return V30Document
     */
    protected function parseFile(string $file): V30Document
    {
        return $this->parse(file_get_contents($file));
    }

    /**
     * @param  string $data
     *
     * @return V30Document
     */
    protected function parse(string $data): V30Document
    {
        $rawData = json_decode($data, true);

        if (!empty($rawData)) {
            $version = $this->detectOpenAPIVersion($rawData);

            switch ($version) {
                case strpos($version, '3.0') !== false:
                    return $this->documentHydrator->hydrate($rawData, new V30Document());
                    break;
            }
        }
    }

    /**
     * @param  array  $rawData
     *
     * @return string
     *
     * @throws CodegenException
     */
    protected function detectOpenAPIVersion(array $rawData): string
    {
        if (array_key_exists('swagger', $rawData)) {
            return $rawData['swagger'];
        }

        if (array_key_exists('openapi', $rawData)) {
            return $rawData['openapi'];
        }

        throw CodegenException::versionDetectFailure();
    }

    /**
     * @param  string $namespace
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     *
     * @throws CodegenException
     */
    protected function getNamespacePath(string $namespace, InputInterface $input, OutputInterface $output): string
    {
        $autoloaders = $this->getComposerAutoloaders();

        foreach ($autoloaders as $registeredNamespace => $path) {
            if (0 === strpos($registeredNamespace, $namespace)) {
                $path = ltrim($path, $this->projectRoot);

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

            if (!is_dir($this->projectRoot . DIRECTORY_SEPARATOR . $namespacePath)) {
                mkdir($this->projectRoot . DIRECTORY_SEPARATOR . $namespacePath, 0755, true);
                $output->writeln('<info>Created folder for namespace: ' . $namespace . '</info>');
            }

            $composerPath = $this->getComposerJsonPath();

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
     * @return array Associative array of namespace/path pairs
     * @throws CodegenException
     */
    private function getComposerAutoloaders() : array
    {
        //Check PSR-4 autoloading from Composer autoload
        $autoloadFile = $this->projectRoot . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'composer' . DIRECTORY_SEPARATOR . '/autoload_psr4.php';

        if (is_file($autoloadFile)) {
            $mapping = array_map(function ($value) {
                return $value[0];
            }, require $autoloadFile);

            return $mapping;
        }

        //Fallback to project composer.json when autoloadfile is not present (yet)
        $composerPath = $this->getComposerJsonPath();
        if (!file_exists($composerPath)) {
            throw CodegenException::missingComposerJson();
        }

        $composer = json_decode(file_get_contents($composerPath), true);

        if (json_last_error() !== \JSON_ERROR_NONE) {
            throw CodegenException::invalidComposerJson(json_last_error_msg());
        }

        if (! isset($composer['autoload']['psr-4'])) {
            throw CodegenException::missingComposerAutoloaders();
        }

        if (! is_array($composer['autoload']['psr-4'])) {
            throw CodegenException::missingComposerAutoloaders();
        }

        return $composer['autoload']['psr-4'];
    }

    /**
     * @return string
     */
    protected function getComposerJsonPath(): string
    {
        return sprintf('%s/composer.json', $this->projectRoot);
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
