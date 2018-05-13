<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;

class ExternalDocumentationHydrator implements HydratorInterface
{
    /**
     * @inheritDoc
     *
     * @param Object\ExternalDocumentation $object
     *
     * @return Object\ExternalDocumentation
     */
    public function hydrate(array $data, $object)
    {
        $object->setDescription($data['description']);
        $object->setUrl($data['url']);

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\ExternalDocumentation $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'description' => $object->getDescription(),
            'url'  => $object->getUrl()
        ];
    }
}
