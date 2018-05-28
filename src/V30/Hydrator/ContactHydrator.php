<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Zend\Hydrator\HydratorInterface;

class ContactHydrator implements HydratorInterface
{
    /**
     * @inheritDoc
     *
     * @param Schema\Contact $object
     *
     * @return Schema\Contact
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
     * @param Schema\Contact $object
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
