<?php

namespace Swagger\V30\Hydrator;

use Laminas\Hydrator\HydratorInterface;
use Swagger\V30\Schema\Schema;
use Swagger\V30\Schema\Example;
use Swagger\V30\Schema\MediaType;
use Swagger\V30\Schema\Reference;
use Swagger\V30\Schema\Header;

class HeaderHydrator implements HydratorInterface
{
    /**
     * @var ReferenceHydrator
     */
    protected $referenceHydrator;

    /**
     * @var ExampleHydrator
     */
    protected $exampleHydrator;

    /**
     * @var MediaTypeHydrator
     */
    protected $mediaTypeHydrator;

    /**
     * @var SchemaHydrator
     */
    protected $schemaHydrator;

    /**
     * Constructor
     */
    public function __construct(
        ReferenceHydrator $referenceHydrator,
        ExampleHydrator $exampleHydrator,
        MediaTypeHydrator $mediaTypeHydrator,
        SchemaHydrator $schemaHydrator
    ) {
        $this->referenceHydrator = $referenceHydrator;
        $this->exampleHydrator = $exampleHydrator;
        $this->mediaTypeHydrator = $mediaTypeHydrator;
        $this->schemaHydrator = $schemaHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Header $object
     *
     * @return Header
     */
    public function hydrate(array $data, $object)
    {
        $object->setDescription($data['description']);

        if (isset($data['required'])) {
            $object->setRequired($data['required']);
        }

        if (isset($data['deprecated'])) {
            $object->setDeprecated($data['deprecated']);
        }

        if (isset($data['allowEmptyValue'])) {
            $object->setAllowEmptyValue($data['allowEmptyValue']);
        }

        if (isset($data['style'])) {
            $object->setStyle($data['style']);
        }

        if (isset($data['explode'])) {
            $object->setExplode($data['explode']);
        }

        if (isset($data['allowReserved'])) {
            $object->setAllowReserved($data['allowReserved']);
        }
        $object->setSchema(isset($data['schema']['$ref'])? $this->referenceHydrator->hydrate($data['schema'], new Reference()) : $this->schemaHydrator->hydrate($data['schema'], new Schema()));

        if (isset($data['example'])) {
            $object->setExample($data['example']);
        }

        if (isset($data['examples'])) {
            foreach ($data['examples'] as $example) {
                $object->addExample(
                    isset($example['$ref'])? $this->referenceHydrator->hydrate($example, new Reference()) : $this->exampleHydrator->hydrate($example, new Example())
                );
            }
        }

        if (isset($data['content'])) {
            foreach ($data['content'] as $mediaType => $content) {
                $object->addContent($mediaType, $this->mediaTypeHydrator->hydrate($content, new MediaType()));
            }
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Header $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'description' => $object->getDescription(),
            'required' => $object->getRequired(),
            'deprecated' => $object->getDeprecated(),
            'allowEmptyValue' => $object->getAllowEmptyValue(),
            'style' => $object->getStyle(),
            'explode' => $object->getExplode(),
            'allowReserved' => $object->getAllowReserved(),
            'schema' => $object->getSchema() instanceof Reference? $this->referenceHydrator->extract($object->getSchema()) : $this->schemaHydrator->extract($object->getSchema()),
            'example' => $object->getExample(),
            'examples' => [],
            'content' => []
        ];

        foreach ($object->getExamples() as $example) {
            $data['examples'][] = $example instanceof Reference? $this->referenceHydrator->extract($example) : $this->exampleHydrator->extract($example);
        }

        foreach ($object->getContent() as $content) {
            $data['content'][] = $this->mediaTypeHydrator->extract($content);
        }

        return $data;
    }
}
