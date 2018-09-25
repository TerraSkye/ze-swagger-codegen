<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Zend\Hydrator\HydratorInterface;
use Swagger\V30\Schema\ExternalDocumentation;

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
     * @param Schema\Tag $object
     *
     * @return Schema\Tag
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
     * @param Schema\Tag $object
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
