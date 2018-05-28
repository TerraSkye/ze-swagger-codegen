<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Zend\Hydrator\HydratorInterface;

class ReferenceHydrator implements HydratorInterface
{
    /**
     * @inheritDoc
     *
     * @param Schema\Reference $object
     *
     * @return Schema\Reference
     */
    public function hydrate(array $data, $object)
    {
        $object->setRef($data['$ref']);

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\Reference $object
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
