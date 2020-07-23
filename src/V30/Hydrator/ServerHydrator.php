<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Laminas\Hydrator\HydratorInterface;
use Swagger\V30\Schema\ServerVariable;

class ServerHydrator implements HydratorInterface
{
    /**
     * @var ServerVariableHydrator
     */
    protected $serverVariableHydrator;

    /**
     * @param ServerVariableHydrator $serverVariableHydrator
     */
    public function __construct(ServerVariableHydrator $serverVariableHydrator)
    {
        $this->serverVariableHydrator = $serverVariableHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\Server $object
     *
     * @return Schema\Server
     */
    public function hydrate(array $data, $object)
    {
        $object->setUrl($data['url']);

        if (isset($data['description'])) {
            $object->setDescription($data['description']);
        }

        if (isset($data['variables'])) {
            foreach ($data['variables'] as $variable) {
                $object->addVariable($this->serverVariableHydrator->hydrate($variable, new ServerVariable()));
            }
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\Server $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'url'  => $object->getUrl(),
            'description' => $object->getDescription()
        ];

        foreach ($object->getVariables() as $variable) {
            $data['variables'][] = $this->serverVariableHydrator->extract($variable);
        }

        return $data;
    }
}
