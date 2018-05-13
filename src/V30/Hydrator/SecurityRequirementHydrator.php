<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;

class SecurityRequirementHydrator implements HydratorInterface
{
    /**
     * @inheritDoc
     *
     * @param Object\SecurityRequirement $object
     *
     * @return Object\SecurityRequirement
     */
    public function hydrate(array $data, $object)
    {
        foreach ($data as $name => $value) {
            $object->setField($name, $value);
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\SecurityRequirement $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [];

        foreach ($object->getFields() as $name => $value) {
            $data[$name] = $value;
        }

        return $data;
    }
}
