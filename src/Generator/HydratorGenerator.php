<?php

namespace Swagger\Generator;

use Swagger\V30\Object\Document;
use Swagger\V30\Object\Schema;
use Swagger\V30\Object\Reference;
use Swagger\Template;

class HydratorGenerator extends AbstractGenerator
{
    /**
     * @var Template
     */
    protected $templateService;

    /**
     * @var ModelGenerator
     */
    protected $modelGenerator;

    /**
     * Constructor
     * ---
     * @param Template $templateService
     * @param ModelGenerator $modelGenerator
     */
    public function __construct(Template $templateService, ModelGenerator $modelGenerator)
    {
        $this->templateService = $templateService;
        $this->modelGenerator = $modelGenerator;
    }

    /**
     * @param  Document $document
     * @param  string   $namespacePath
     * @param  string $namespace
     */
    public function generateFromDocument(Document $document, string $namespacePath, string $namespace)
    {
        foreach ($document->getComponents()->getSchemas() as $name => $schema) {
            /** @var Schema $schema **/

            $this->generateFromSchema($schema, $name, $namespacePath, $namespace);
        }
    }

    /**
     * @param  Schema   $schema
     * @param  string   $name
     * @param  string   $namespacePath
     * @param  string   $namespace
     *
     * @return string|null
     */
    public function generateFromSchema(
        Schema $schema,
        string $name,
        string $namespacePath,
        string $namespace
    ): ?string {
        /** @var Schema|Reference $schema **/

        $hydratorPath = $namespacePath . DIRECTORY_SEPARATOR . 'Hydrator' . DIRECTORY_SEPARATOR;
        $hydratorNamespace = $this->getNamespace($namespace);
        $modelNamespace = $this->modelGenerator->getNamespace($namespace);

        $modelName = $this->toModelName($name);
        $path = $hydratorPath . $modelName . 'Hydrator.php';

        if (true || !is_file($path)) {
            $hydrator = $this->templateService->render('model-hydrator', [
                'className'  => $modelName . 'Hydrator',
                'namespace'  => $hydratorNamespace,
                'modelName' => $modelName,
                'modelNamespace' => $modelNamespace,
                'properties' => $this->modelGenerator->getModelProperties($schema, $name)
            ]);

            $this->writeFile($path, $hydrator);

            return $modelName . 'Hydrator';
        }
    }

    /**
     * @param  string $namespace
     *
     * @return string
     */
    public function getNamespace(string $namespace): string
    {
        return $namespace . '\Hydrator';
    }

    /**
     * @param  Document $document
     * @param  string   $namespace
     *
     * @return string[]
     */
    public function getHydratorClasses(Document $document, string $namespace)
    {
        $hydratorNamespace = $this->getNamespace($namespace);
        $modelNamespace = $this->modelGenerator->getNamespace($namespace);

        $hydrators = [];
        foreach (array_keys($document->getComponents()->getSchemas()) as $name) {
            /** @var Schema|Reference $schema **/

            $modelName = $this->toModelName($name);

            $hydrators[] = [
                'hydrator' => $hydratorNamespace . '\\' . $modelName . 'Hydrator::class',
                'model'    => $modelNamespace . '\\' . $modelName . '::class'
            ];
        }

        return $hydrators;
    }
}
