<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;
use Swagger\V30\Object\ExternalDocumentation;

class TagHydrator implements HydratorInterface
{
    /**
     * @var ExternalDocumentationHydrator
     */
    protected $externalDocsHydrator;

    /**
     * @param ExternalDocumentationHydrator $externalDocsHydrator
     */
    public function __construct(
        ExternalDocumentationHydrator $externalDocsHydrator
    ) {
        $this->externalDocsHydrator = $externalDocsHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Tag $object
     *
     * @return Object\Tag
     */
    public function hydrate(array $data, $object)
    {
        $object->setName($data['name']);
        $object->setDescription($data['description']);

        if (isset($data['externalDocs'])) {
            $object->setExternalDocs($this->externalDocsHydrator->hydrate($data['externalDocs'], new ExternalDocumentation()));
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Tag $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'name' => $object->getName(),
            'description' => $object->getDescription(),
            'externalDocs'    => $this->externalDocsHydrator->extract($object->getExternalDocs()),
        ];
    }
}
