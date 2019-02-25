<?php

namespace Swagger\Generator;

use Swagger\Exception\CodegenException;

use Swagger\V30\Schema\Document;
use Swagger\V30\Schema\PathItem;
use Swagger\Template;
use Swagger\Ignore;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class HandlerGenerator extends AbstractGenerator
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
        foreach ($document->getPaths() as $path => $pathItem) {
            /** @var PathItem $pathItem **/

            $this->generateFromPathItem($pathItem, $path, $namespacePath, $namespace);
        }
    }

    /**
     * @param  PathItem $pathItem
     * @param  string   $path
     * @param  string   $namespacePath
     * @param  string   $namespace
     *
     * @return string|null
     */
    public function generateFromPathItem(
        PathItem $pathItem,
        string $path,
        string $namespacePath,
        string $namespace
    ): ?string {
        /** @var PathItem $pathItem **/

        $handlerName = $this->getHandlerName($path);

        $handlerPath = $namespacePath . DIRECTORY_SEPARATOR . 'Handler' . DIRECTORY_SEPARATOR;

        $savePath = $handlerPath . $handlerName . '.php';

        if (!$this->ignoreService->isIgnored($savePath)) {
            $handler = $this->templateService->render('handler', [
                'className'  => $handlerName,
                'namespace'  => $this->getNamespace($namespace),
                'operationMethods' => array_keys($pathItem->getOperations())
            ]);

            if ($this->writeFile($savePath, $handler) === false) {
                throw new CodegenException(sprintf('Failed to write "%s" to "%s"', $handlerName, $savePath));
            }

            $this->eventDispatcher->dispatch('swagger.codegen.generator.generated', new GenericEvent([
                'generator' => 'Handler',
                'name' => $handlerName
            ]));

            return $handlerName;
        }

        return null;
    }

    /**
     * @param  string $path
     *
     * @return string
     */
    public function getHandlerName(string $path): string
    {
        return $this->toModelName($path) . 'Handler';
    }

    /**
     * @param  string $namespace
     *
     * @return string
     */
    public function getNamespace(string $namespace): string
    {
        return $namespace . '\Handler';
    }
}
