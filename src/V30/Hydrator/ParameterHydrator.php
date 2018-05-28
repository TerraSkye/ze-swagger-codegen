<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema\Parameter;
use Zend\Hydrator\HydratorInterface;
use Swagger\V30\Schema\Schema;
use Swagger\V30\Schema\Example;
use Swagger\V30\Schema\MediaType;
use Swagger\V30\Schema\Reference;

class ParameterHydrator implements HydratorInterface
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
     * @param ReferenceHydrator $referenceHydrator
     * @param ExampleHydrator   $exampleHydrator
     * @param MediaTypeHydrator $mediaTypeHydrator
     * @param SchemaHydrator    $schemaHydrator
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
     * @param Parameter $object
     *
     * @return Parameter
     */
    public function hydrate(array $data, $object)
    {
        $object->setName($data['name']);
        $object->setIn($data['in']);
        $object->setDescription($data['description']);
        $object->setRequired($data['required']);

        if(isset($data['deprecated'])) {
            $object->setDeprecated($data['deprecated']);
        }

        if(isset($data['allowEmptyValue'])) {
            $object->setAllowEmptyValue($data['allowEmptyValue']);
        }

        if(isset($data['style'])) {
            $object->setStyle($data['style']);
        }

        if(isset($data['explode'])) {
            $object->setExplode($data['explode']);
        }

        if(isset($data['allowReserved'])) {
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
     * @param Parameter $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'name' => $object->getName(),
            'in'  => $object->getIn(),
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
