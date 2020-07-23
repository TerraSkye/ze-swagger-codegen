<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Laminas\Hydrator\HydratorInterface;

class ExampleHydrator implements HydratorInterface
{
    /**
     * @inheritDoc
     *
     * @param Schema\Example $object
     *
     * @return Schema\Example
     */
    public function hydrate(array $data, $object)
    {
        $object->setSummary($data['summary']);
        $object->setDescription($data['description']);
        $object->setValue($data['value']);
        $object->setExternalValue($data['externalValue']);

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\Example $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'summary' => $object->getSummary(),
            'description'  => $object->getDescription(),
            'value' => $object->getValue(),
            'externalValue' => $object->getExternalValue()
        ];
    }
}
