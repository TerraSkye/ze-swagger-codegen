<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Laminas\Hydrator\HydratorInterface;
use Swagger\V30\Schema\Response;
use Swagger\V30\Schema\Reference;

class ResponsesHydrator implements HydratorInterface
{
    /**
     * @var ResponseHydrator
     */
    protected $responseHydrator;

    /**
     * @var ReferenceHydrator
     */
    protected $referenceHydrator;

    /**
     * Constructor
     * ---
     * @param ResponseHydrator  $responseHydrator
     * @param ReferenceHydrator $referenceHydrator
     */
    public function __construct(
        ResponseHydrator $responseHydrator,
        ReferenceHydrator $referenceHydrator
    ) {
        $this->responseHydrator = $responseHydrator;
        $this->referenceHydrator = $referenceHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\Responses $object
     *
     * @return Schema\Responses
     */
    public function hydrate(array $data, $object)
    {
        if(isset($data['default'])){
            $object->setDefault(isset($data['default']['$ref'])? $this->referenceHydrator->hydrate($data['default'] , new Reference()): $this->responseHydrator->hydrate($data['default'], new Response()));
        }

        foreach ($data as $statusCode => $response) {
            if ($statusCode >= 100 && $statusCode <= 599) {
                $object->setStatusResponse($statusCode, isset($response['$ref'])? $this->referenceHydrator->hydrate($response, new Reference()): $this->responseHydrator->hydrate($response, new Response()));
            }
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\Responses $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'default' => $object->getDefault() instanceof Reference? $this->referenceHydrator->extract($object->getDefault()): $this->responseHydrator->extract($object->getDefault())
        ];

        foreach ($object->getStatusResponses() as $statusCode => $response) {
            $data[$statusCode] = $response instanceof Reference? $this->referenceHydrator->extract($response): $this->responseHydrator->extract($response);
        }

        return $data;
    }
}
