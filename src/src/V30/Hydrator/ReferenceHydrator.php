<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;

class ReferenceHydrator implements HydratorInterface
{
    /**
     * @inheritDoc
     *
     * @param Object\Reference $object
     *
     * @return Object\Reference
     */
    public function hydrate(array $data, $object)
    {
        $object->setRef($data['$ref']);

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Reference $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            '$ref' => $object->getRef()
        ];
    }
}
