<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;

use Swagger\V30\Object\Operation;
use Swagger\V30\Object\Parameter;

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
     * @param OperationHydrator $operationHydrator
     * @param ServerHydrator    $serverHydrator
     * @param ParameterHydrator $parameterHydrator
     */
    public function __construct(
        OperationHydrator $operationHydrator,
        ServerHydrator $serverHydrator,
        ParameterHydrator $parameterHydrator
    ) {
        $this->operationHydrator = $operationHydrator;
        $this->serverHydrator = $serverHydrator;
        $this->parameterHydrator = $parameterHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Object\PathItem $object
     *
     * @return Object\PathItem
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
     * @param Object\PathItem $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'enum'  => $object->getEnum(),
            'default'  => $object->getDefault(),
            'description' => $object->getDescription()
        ];
    }
}
