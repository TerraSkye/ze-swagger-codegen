<?php

namespace Swagger\Generator;

use Swagger\V30\Object\Document;
use Swagger\Template;

class DependenciesGenerator extends AbstractGenerator
{
    /**
     * @var Template
     */
    protected $templateService;

    /**
     * @var HydratorGenerator
     */
    protected $hydratorGenerator;

    /**
     * @var ModelGenerator
     */
    protected $modelGenerator;

    /**
     * Constructor
     * ---
     * @param Template $templateService
     * @param HydratorGenerator $hydratorGenerator
     * @param ModelGenerator $modelGenerator
     */
    public function __construct(
        Template $templateService,
        HydratorGenerator $hydratorGenerator,
        ModelGenerator $modelGenerator
    ) {
        $this->templateService = $templateService;
        $this->hydratorGenerator = $hydratorGenerator;
        $this->modelGenerator = $modelGenerator;
    }

    /**
     * @param  Document $document
     * @param  string   $namespace
     * @param  string $configPath
     */
    public function generateFromDocument(Document $document, string $namespace, string $configPath)
    {
        $dependencies = $this->templateService->render('dependencies', [
            'models'    => $this->modelGenerator->getModelClasses($document, $namespace),
            'hydrators' => $this->hydratorGenerator->getHydratorClasses($document, $namespace)
        ]);

        $dependencyConfigPath = rtrim($configPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'autoload' . DIRECTORY_SEPARATOR . 'swagger.dependencies.global.php';

        $this->writeFile($dependencyConfigPath, $dependencies);
    }
}
