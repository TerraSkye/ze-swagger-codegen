<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Zend\Hydrator\HydratorInterface;

use Swagger\V30\Schema\MediaType;

class RequestBodyHydrator implements HydratorInterface
{
    /**
     * @var MediaTypeHydrator
     */
    protected $mediaTypeHydrator;

    /**
     * Constructor
     * ---
     * @param MediaTypeHydrator $mediaTypeHydrator
     */
    public function __construct(MediaTypeHydrator $mediaTypeHydrator)
    {
        $this->mediaTypeHydrator = $mediaTypeHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\RequestBody $object
     *
     * @return Schema\RequestBody
     */
    public function hydrate(array $data, $object)
    {
        $object->setDescription($data['description']);

        if (isset($data['content'])) {
            foreach ($data['content'] as $mediaType => $content) {
                $object->addContent($mediaType, $this->mediaTypeHydrator->hydrate($content, new MediaType()));
            }
        }

        $object->setRequired($data['required']);

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\RequestBody $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'description' => $object->getDescription(),
            'required'  => $object->getRequired()
        ];

        foreach ($object->getContent() as $content) {
            $data['content'][] = $this->mediaTypeHydrator->extract($content);
        }

        return $data;
    }
}
