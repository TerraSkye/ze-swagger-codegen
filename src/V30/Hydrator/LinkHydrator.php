<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Laminas\Hydrator\HydratorInterface;
use Swagger\V30\Schema\Server;

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
     * @param Schema\Link $object
     *
     * @return Schema\Link
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
     * @param Schema\Link $object
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
