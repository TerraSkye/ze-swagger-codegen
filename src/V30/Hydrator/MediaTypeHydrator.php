<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema\MediaType;
use Laminas\Hydrator\HydratorInterface;
use Swagger\V30\Schema\Schema;
use Swagger\V30\Schema\Example;
use Swagger\V30\Schema\Encoding;
use Swagger\V30\Schema\Reference;

class MediaTypeHydrator implements HydratorInterface
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
     * @var EncodingHydrator
     */
    protected $encodingHydrator;

    /**
     * @var SchemaHydrator
     */
    protected $schemaHydrator;

    /**
     * @param ReferenceHydrator $referenceHydrator
     * @param ExampleHydrator   $exampleHydrator
     * @param EncodingHydrator  $encodingHydrator
     * @param SchemaHydrator    $schemaHydrator
     */
    public function __construct(
        ReferenceHydrator $referenceHydrator,
        ExampleHydrator $exampleHydrator,
        EncodingHydrator $encodingHydrator,
        SchemaHydrator $schemaHydrator
    ) {
        $this->referenceHydrator = $referenceHydrator;
        $this->exampleHydrator = $exampleHydrator;
        $this->encodingHydrator = $encodingHydrator;
        $this->schemaHydrator = $schemaHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param MediaType $object
     *
     * @return MediaType
     */
    public function hydrate(array $data, $object)
    {
        $object->setSchema(isset($data['schema']['$ref'])? $this->referenceHydrator->hydrate($data['schema'], new Reference()) : $this->schemaHydrator->hydrate($data['schema'], new Schema()));

        if (isset($data['example'])) {
            $object->setExample($data['example']);
        }

        if(isset($data['examples'])) {
            foreach ($data['examples'] as $example) {
                $object->addExample(
                    isset($example['$ref'])? $this->referenceHydrator->hydrate($example, new Reference()) : $this->exampleHydrator->hydrate($example, new Example())
                );
            }
        }

        if(isset($data['encoding'])) {
            foreach ($data['encoding'] as $encoding) {
                $object->addEncoding(
                    $this->encodingHydrator->hydrate($encoding, new Encoding())
                );
            }
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param MediaType $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'schema' => $object->getSchema() instanceof Reference? $this->referenceHydrator->extract($object->getSchema()) : $this->schemaHydrator->extract($object->getSchema()),
            'example'  => $object->getExample()
        ];

        foreach ($object->getExamples() as $example) {
            $data['examples'][] = $example instanceof Reference? $this->referenceHydrator->extract($example) : $this->exampleHydrator->extract($example);
        }

        foreach ($object->getEncoding() as $encoding) {
            $data['encoding'][] = $this->encodingHydrator->extract($encoding);
        }

        return $data;
    }
}
