<?php

namespace Swagger\Generator;

use Swagger\V30\Schema\Document;
use Swagger\V30\Schema\Schema;
use Swagger\V30\Schema\Reference;
use Swagger\Template;
use Swagger\Ignore;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ModelGenerator extends AbstractGenerator
{
    /**
     * @var Template
     */
    protected $templateService;

    /**
     * @var Ignore
     */
    protected $ignoreService;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Constructor
     * ---
     * @param Template $templateService
     * @param Ignore $ignoreService
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        Template $templateService,
        Ignore $ignoreService,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->templateService = $templateService;
        $this->ignoreService = $ignoreService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param  Document $document
     * @param  string   $namespacePath
     * @param  string $namespace
     */
    public function generateFromDocument(Document $document, string $namespacePath, string $namespace)
    {
        if ($document->getComponents()) {
            foreach ($document->getComponents()->getSchemas() as $name => $schema) {
                /** @var Schema $schema **/

                if ($schema instanceof Schema) {
                    $this->generateFromSchema($schema, $name, $namespacePath, $namespace);
                }
                //@TODO Reference
            }
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
        $modelPath = $namespacePath . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR;

        $modelName = $this->toModelName($name);
        $path = $modelPath . $modelName . '.php';

        if (!$this->ignoreService->isIgnored($path)) {
            $hydratorNamespace = $namespace . '\Hydrator';

            $model = $this->templateService->render('model', [
                'className'  => $modelName,
                'namespace'  => $this->getNamespace($namespace),
                'properties' => $this->getModelProperties($schema, $name),
                'hydrator'   => $hydratorNamespace . '\\' . $modelName . 'Hydrator'
            ]);

            $this->writeFile($path, $model);

            $this->eventDispatcher->dispatch('swagger.codegen.generator.generated', new GenericEvent([
                'generator' => 'Model',
                'name' => $modelName
            ]));

            return $modelName;
        }

        return null;
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
                    'getter'    => 'get' . $this->getCamelCaseFilter()->filter($name),
                    'setter'    => 'set' . $this->getCamelCaseFilter()->filter($name),
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
                    'getter'    => 'get' . $this->getCamelCaseFilter()->filter($name . 's'),
                    'setter'    => 'set' . $this->getCamelCaseFilter()->filter($name . 's'),
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
        if ($document->getComponents()) {
            foreach (array_keys($document->getComponents()->getSchemas()) as $name) {
                /** @var Schema|Reference $schema **/

                $modelName = $this->toModelName($name);

                $models[] = $modelNamespace . '\\' . $modelName . '::class';
            }
        }

        return $models;
    }
}
