<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Zend\Hydrator\HydratorInterface;

class ExternalDocumentationHydrator implements HydratorInterface
{
    /**
     * @inheritDoc
     *
     * @param Schema\ExternalDocumentation $object
     *
     * @return Schema\ExternalDocumentation
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
     * @param Schema\ExternalDocumentation $object
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
