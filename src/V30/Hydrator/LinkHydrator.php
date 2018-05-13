<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;
use Swagger\V30\Object\Server;

class LinkHydrator implements HydratorInterface
{
    /**
     * @var ServerHydrator
     */
    protected $serverHydrator;

    /**
     * Constructor
     * ---
     * @param ServerHydrator $serverHydrator
     */
    public function __construct(ServerHydrator $serverHydrator)
    {
        $this->serverHydrator = $serverHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Link $object
     *
     * @return Object\Link
     */
    public function hydrate(array $data, $object)
    {
        $object->setOperationRef($data['operationRef']);
        $object->setOperationId($data['operationId']);
        $object->setParameters($data['parameters']);
        $object->setRequestBody($data['requestBody']);
        $object->setDescription($data['description']);

        $object->setServer($this->serverHydrator->hydrate($data['server'], new Server()));

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Link $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'operationRef' => $object->getOperationRef(),
            'operationId'  => $object->getOperationId(),
            'parameters' => $object->getParameters(),
            'requestBody' => $object->getRequestBody(),
            'description' => $object->getDescription(),
            'server' => $this->serverHydrator->extract($object->getServer())
        ];
    }
}
