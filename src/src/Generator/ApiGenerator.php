<?php

namespace Swagger\Generator;

use Swagger\V30\Object\Document;
use Swagger\V30\Object\PathItem;
use Swagger\V30\Object\Operation;
use Swagger\V30\Object\Parameter;
use Swagger\V30\Object\MediaType;
use Swagger\Template;

use Swagger\V30\Object\Reference;

class ApiGenerator extends AbstractGenerator
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
        $operations = $this->getOperationsGroupedByTag($document);

        foreach ($operations as $tag => $paths) {
            $apiName = $this->getApiName($tag);

            $apiPath = $namespacePath . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR;

            $savePath = $apiPath . $apiName . '.php';

            if (true || !is_file($savePath)) {
                $ops = [];

                foreach ($paths as $path => $httpMethods) {
                    foreach ($httpMethods as $httpMethod => $operation) {
                        /** @var Operation $operation **/

                        $parameters = [];

                        foreach ($operation->getParameters() as $parameter) {
                            /** @param Parameter $parameter **/

                            $parameters[] = [
                                'name' => $parameter->getName(),
                                'dataType' => $parameter->getSchema()->getPhpType(),
                            ];
                        }

                        $requestBody = [];
                        if ($reqBody = $operation->getRequestBody()) {
                            foreach ($reqBody->getContent() as $mediaType => $content) {
                                /** @var MediaType $content **/
                                $schema = $content->getSchema();

                                $dataType = $schema instanceof Reference? substr($schema->getRef(), strlen('#/components/schemas/')) : $schema->getPhpType();

                                $requestBody['mediaTypes'][] = [
                                    'mediaType' => $mediaType,
                                    'dataType' => $dataType
                                ];
                            }
                            if (count($requestBody['mediaTypes']) == 1) {
                                $requestBody['dataType'] = $requestBody['mediaTypes'][0]['dataType'];
                            }
                        }

                        $ops[] = [
                            'operationId' => $operation->getOperationId(),
                            'summary' => $operation->getSummary(),
                            'description' => $operation->getDescription(),
                            'parameters' => $parameters,
                            'requestBody' => $requestBody
                        ];
                    }
                }

                $api = $this->templateService->render('api', [
                    'className'  => $apiName,
                    'namespace'  => $this->getNamespace($namespace),
                    'operations' => $ops
                ]);

                print_r($api);exit;

                $this->writeFile($savePath, $api);
            }
        }
        exit;
        // foreach ($document->getPaths() as $path => $pathItem) {
        //     /** @var PathItem $pathItem **/
        //
        //     $this->generateFromPathItem($pathItem, $path, $namespacePath, $namespace);
        // }
    }

    /**
     * @param  Document $document
     *
     * @return array
     */
    public function getOperationsGroupedByTag(Document $document)
    {
        $operations = [];
        foreach ($document->getPaths() as $path => $pathItem) {
            foreach ($pathItem->getOperations() as $httpMethod => $operation) {
                $tags = empty($operation->getTags())? ['default']: $operation->getTags();
                foreach ($operation->getTags() as $tag) {
                    $operations[$tag][$path][$httpMethod] = $operation;
                }
            }
        }

        return $operations;
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

        $apiName = $this->getApiName($path);

        $apiPath = $namespacePath . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR;

        $savePath = $apiPath . $apiName . '.php';

        var_dump($path);exit;

        if (true || !is_file($savePath)) {
            $api = $this->templateService->render('api', [
                'className'  => $apiName,
                'namespace'  => $this->getNamespace($namespace),
                'operationMethods' => array_keys($pathItem->getOperations())
            ]);

            $this->writeFile($savePath, $api);

            return $apiName;
        }
    }

    /**
     * @param  string $path
     *
     * @return string
     */
    public function getApiName(string $path): string
    {
        return $this->toModelName($path) . 'Api';
    }

    /**
     * @param  string $namespace
     *
     * @return string
     */
    public function getNamespace(string $namespace): string
    {
        return $namespace . '\Api';
    }
}
