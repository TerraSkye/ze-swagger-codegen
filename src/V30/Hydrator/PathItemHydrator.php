<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Zend\Hydrator\HydratorInterface;
use Swagger\V30\Schema\Operation;
use Swagger\V30\Schema\Parameter;
use Swagger\V30\Schema\Server;
use Swagger\V30\Schema\Reference;

class PathItemHydrator implements HydratorInterface
{
    /**
     * @var OperationHydrator
     */
    protected $operationHydrator;

    /**
     * @var ServerHydrator
     */
    protected $serverHydrator;

    /**
     * @var ParameterHydrator
     */
    protected $parameterHydrator;

    /**
     * @var ReferenceHydrator
     */
    protected $referenceHydrator;

    /**
     * @param OperationHydrator $operationHydrator
     * @param ServerHydrator    $serverHydrator
     * @param ParameterHydrator $parameterHydrator
     * @param ReferenceHydrator $referenceHydrator
     */
    public function __construct(
        OperationHydrator $operationHydrator,
        ServerHydrator $serverHydrator,
        ParameterHydrator $parameterHydrator,
        ReferenceHydrator $referenceHydrator
    ) {
        $this->operationHydrator = $operationHydrator;
        $this->serverHydrator = $serverHydrator;
        $this->parameterHydrator = $parameterHydrator;
        $this->referenceHydrator = $referenceHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\PathItem $object
     *
     * @return Schema\PathItem
     */
    public function hydrate(array $data, $object)
    {
        if (isset($data['$ref'])) {
            $object->setRef($data['$ref']);
        }

        if (isset($data['summary'])) {
            $object->setSummary($data['summary']);
        }

        if (isset($data['description'])) {
            $object->setDescription($data['description']);
        }

        if (isset($data['get'])) {
            $object->setGet($this->operationHydrator->hydrate($data['get'], new Operation()));
        }

        if (isset($data['put'])) {
            $object->setPut($this->operationHydrator->hydrate($data['put'], new Operation()));
        }

        if (isset($data['post'])) {
            $object->setPost($this->operationHydrator->hydrate($data['post'], new Operation()));
        }

        if (isset($data['delete'])) {
            $object->setDelete($this->operationHydrator->hydrate($data['delete'], new Operation()));
        }

        if (isset($data['options'])) {
            $object->setOptions($this->operationHydrator->hydrate($data['options'], new Operation()));
        }

        if (isset($data['head'])) {
            $object->setHead($this->operationHydrator->hydrate($data['head'], new Operation()));
        }

        if (isset($data['patch'])) {
            $object->setPatch($this->operationHydrator->hydrate($data['patch'], new Operation()));
        }

        if (isset($data['trace'])) {
            $object->setTrace($this->operationHydrator->hydrate($data['trace'], new Operation()));
        }

        if (isset($data['servers'])) {
            foreach ($data['servers'] as $server) {
                $object->addServer($this->serverHydrator->hydrate($server, new Server()));
            }
        }

        if (isset($data['parameters'])) {
            foreach ($data['parameters'] as $parameter) {
                $object->addParameter($this->parameterHydrator->hydrate($parameter, new Parameter()));
            }
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\PathItem $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            '$ref'  => $object->getRef(),
            'summary'  => $object->getSummary(),
            'description' => $object->getDescription(),
            'get' => $object->getGet()? $this->operationHydrator->extract($object->getGet()):null,
            'put' => $object->getGet()? $this->operationHydrator->extract($object->getPut()):null,
            'post' => $object->getGet()? $this->operationHydrator->extract($object->getPost()):null,
            'delete' => $object->getGet()? $this->operationHydrator->extract($object->getDelete()):null,
            'options' => $object->getGet()? $this->operationHydrator->extract($object->getOptions()):null,
            'head' => $object->getGet()? $this->operationHydrator->extract($object->getHead()):null,
            'patch' => $object->getGet()? $this->operationHydrator->extract($object->getPatch()):null,
            'trace' => $object->getGet()? $this->operationHydrator->extract($object->getTrace()):null,
            'servers' => [],
            'parameters' => []
        ];

        foreach ($object->getServers() as $server) {
            $data['servers'][] = $this->serverHydrator->extract($server);
        }

        foreach ($object->getParameters() as $parameter) {
            $data['parameters'][] = $parameter instanceof Reference? $this->referenceHydrator->extract($parameter) :$this->parameterHydrator->extract($parameter);
        }

        return $data;
    }
}
