<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Laminas\Hydrator\HydratorInterface;

class ServerVariableHydrator implements HydratorInterface
{
    /**
     * @inheritDoc
     *
     * @param Schema\ServerVariable $object
     *
     * @return Schema\ServerVariable
     */
    public function hydrate(array $data, $object)
    {
        $object->setEnum($data['enum']);
        $object->setDefault($data['default']);
        $object->setDescription($data['description']);

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\ServerVariable $object
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
