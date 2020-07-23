<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Laminas\Hydrator\HydratorInterface;

class SecurityRequirementHydrator implements HydratorInterface
{
    /**
     * @inheritDoc
     *
     * @param Schema\SecurityRequirement $object
     *
     * @return Schema\SecurityRequirement
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
     * @param Schema\SecurityRequirement $object
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
