<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;

class ContactHydrator implements HydratorInterface
{
    /**
     * @inheritDoc
     *
     * @param Object\Contact $object
     *
     * @return Object\Contact
     */
    public function hydrate(array $data, $object)
    {
        $object->setName($data['name']);
        $object->setUrl($data['url']);
        $object->setEmail($data['email']);

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Contact $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'name' => $object->getName(),
            'url'  => $object->getUrl(),
            'email'=> $object->getEmail()
        ];
    }
}
