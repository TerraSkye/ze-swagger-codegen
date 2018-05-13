<?php

namespace Swagger\Generator;

use Swagger\V30\Object\Document;
use Swagger\V30\Object\Schema;
use Swagger\V30\Object\Reference;
use Swagger\Template;

class ModelGenerator extends AbstractGenerator
{
    /**
     * @var Template
     */
    protected $templateService;

    /**
     * Constructor
     * ---
     * @param Template $templateService
     */
    public function __construct(Template $templateService)
    {
        $this->templateService = $templateService;
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

        $modelPath = $namespacePath . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR;

        $modelName = $this->toModelName($name);
        $path = $modelPath . $modelName . '.php';

        if (true || !is_file($path)) {
            $hydratorNamespace = $namespace . '\Hydrator';

            $model = $this->templateService->render('model', [
                'className'  => $modelName,
                'namespace'  => $this->getNamespace($namespace),
                'properties' => $this->getModelProperties($schema, $name),
                'hydrator'   => $hydratorNamespace . '\\' . $modelName . 'Hydrator'
            ]);

            $this->writeFile($path, $model);

            return $modelName;
        }
    }

    /**
     * @param  string $namespace
     *
     * @return string
     */
    public function getNamespace(string $namespace): string
    {
        return $namespace . '\Model';
    }

    public function getModelProperties(Schema $schema, string $name)
    {
        $properties = [];

        if ($schema->isObject()) {
            foreach ($schema->getProperties() as $name => $property) {
                /** @var Schema|Reference $property */

                $validators = [];

                if ($schema->isRequired($name)) {
                    $validators[] = \Zend\Validator\NotEmpty::class;
                }

                $properties[] = [
                    'name'      => $name,
                    'typeHint'  => $property->getPhpType(),
                    'getter'    => 'get' . $this->getCamelcaseFilter()->filter($name),
                    'setter'    => 'set' . $this->getCamelcaseFilter()->filter($name),
                    'validators' => $validators
                ];
            }
        } elseif ($schema->isArray()) {
            if ($schema->getItems() instanceof Reference) {
                $validators = [];

                if ($schema->isRequired(lcfirst($name) . 's')) {
                    $validators[] = \Zend\Validator\NotEmpty::class;
                }

                $name = substr($schema->getItems()->getRef(), strlen('#/components/schemas/'));
                $properties[] = [
                    'name'      => lcfirst($name) . 's',
                    'typeHint'  => 'array',
                    'docTypeHint' => $name . '[]',
                    'defaultValue' => '[]',
                    'getter'    => 'get' . $this->getCamelcaseFilter()->filter($name . 's'),
                    'setter'    => 'set' . $this->getCamelcaseFilter()->filter($name . 's'),
                    'validators' => $validators
                ];
            }
        }

        return $properties;
    }

    /**
     * @param  Document $document
     * @param  string   $namespace
     * 
     * @return string[]
     */
    public function getModelClasses(Document $document, string $namespace)
    {
        $modelNamespace = $this->getNamespace($namespace);

        $models = [];
        foreach (array_keys($document->getComponents()->getSchemas()) as $name) {
            /** @var Schema|Reference $schema **/

            $modelName = $this->toModelName($name);

            $models[] = $modelNamespace . '\\' . $modelName . '::class';
        }

        return $models;
    }
}
