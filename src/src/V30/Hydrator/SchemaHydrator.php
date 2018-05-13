<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;

class SchemaHydrator implements HydratorInterface
{
    /**
     * @var ReferenceHydrator
     */
    protected $referenceHydrator;

    /**
     * @param ReferenceHydrator $referenceHydrator
     */
    public function __construct(
        ReferenceHydrator $referenceHydrator
    ) {
        $this->referenceHydrator = $referenceHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Schema $object
     *
     * @return Object\Schema
     */
    public function hydrate(array $data, $object)
    {
        if (isset($data['type'])) {
            $object->setType($data['type']);
        }

        if (isset($data['format'])) {
            $object->setFormat($data['format']);
        }

        if (isset($data['properties'])) {
            foreach ($data['properties'] as $name => $property) {
                $object->setProperty($name, $this->hydrate($property, new Object\Schema($name)));
            }
        }

        if (isset($data['items'])) {
            $object->setItems(isset($data['items']['$ref'])? $this->referenceHydrator->hydrate($data['items'], new Object\Reference()) : $this->hydrate($data['items'], new Object\Schema()));
        }

        if (isset($data['required'])) {
            foreach ($data['required'] as $required) {
                $object->addRequired($required);
            }
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Schema $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'type'   => $object->getType(),
            'format' => $object->getFormat(),
            'items'  => $object->getItems() instanceof Object\Reference? $this->referenceHydrator->extract($object->getItems()): $this->extract($object->getItems())
        ];

        foreach ($object->getProperties() as $name => $property) {
            $data['properties'][$name] = $this->extract($property);
        }

        return $data;
    }
}
