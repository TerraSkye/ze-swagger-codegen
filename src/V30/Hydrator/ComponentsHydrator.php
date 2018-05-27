<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;
use Swagger\V30\Object\Link;
use Swagger\V30\Object\Header;
use Swagger\V30\Object\Schema;
use Swagger\V30\Object\Example;
use Swagger\V30\Object\Callback;
use Swagger\V30\Object\Response;
use Swagger\V30\Object\Parameter;
use Swagger\V30\Object\Reference;
use Swagger\V30\Object\RequestBody;
use Swagger\V30\Object\SecurityScheme;

class ComponentsHydrator implements HydratorInterface
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
     * @var SchemaHydrator
     */
    protected $schemaHydrator;

    /**
     * @var ResponseHydrator
     */
    protected $responseHydrator;

    /**
     * @var ParameterHydrator
     */
    protected $parameterHydrator;

    /**
     * @var RequestBodyHydrator
     */
    protected $requestBodyHydrator;

    /**
     * @var HeaderHydrator
     */
    protected $headerHydrator;

    /**
     * @var SecuritySchemeHydrator
     */
    protected $securitySchemeHydrator;

    /**
     * @var LinkHydrator
     */
    protected $linkHydrator;

    /**
     * @var CallbackHydrator
     */
    protected $callbackHydrator;

    /**
     * Constructor
     * ---
     * @param ReferenceHydrator $referenceHydrator
     * @param ExampleHydrator   $exampleHydrator
     * @param SchemaHydrator    $schemaHydrator
     * @param ResponseHydrator $responseHydrator
     * @param ParameterHydrator $parameterHydrator
     * @param RequestBodyHydrator $requestBodyHydrator
     * @param HeaderHydrator $headerHydrator
     * @param SecuritySchemeHydrator $securitySchemeHydrator
     * @param LinkHydrator $linkHydrator
     * @param CallbackHydrator $callbackHydrator
     */
    public function __construct(
        ReferenceHydrator $referenceHydrator,
        ExampleHydrator $exampleHydrator,
        SchemaHydrator $schemaHydrator,
        ResponseHydrator $responseHydrator,
        ParameterHydrator $parameterHydrator,
        RequestBodyHydrator $requestBodyHydrator,
        HeaderHydrator $headerHydrator,
        SecuritySchemeHydrator $securitySchemeHydrator,
        LinkHydrator $linkHydrator,
        CallbackHydrator $callbackHydrator
    ) {
        $this->referenceHydrator = $referenceHydrator;
        $this->exampleHydrator = $exampleHydrator;
        $this->schemaHydrator = $schemaHydrator;
        $this->responseHydrator = $responseHydrator;
        $this->parameterHydrator = $parameterHydrator;
        $this->requestBodyHydrator = $requestBodyHydrator;
        $this->headerHydrator = $headerHydrator;
        $this->securitySchemeHydrator = $securitySchemeHydrator;
        $this->linkHydrator = $linkHydrator;
        $this->callbackHydrator = $callbackHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Components $object
     *
     * @return Object\Components
     */
    public function hydrate(array $data, $object)
    {
        if(isset($data['schemas'])) {
            foreach ($data['schemas'] as $name => $schema) {
                $object->addSchema($name, isset($schema['def'])? $this->referenceHydrator->hydrate($schema, new Reference()) : $this->schemaHydrator->hydrate($schema, new Schema($name)));
            }
        }

        if(isset($data['responses'])) {
            foreach ($data['responses'] as $name => $response) {
                $object->addResponse($name, isset($response['def'])? $this->referenceHydrator->hydrate($response, new Reference()) : $this->responseHydrator->hydrate($response, new Response()));
            }
        }

        if(isset($data['parameters'])) {
            foreach ($data['parameters'] as $name => $parameter) {
                $object->addParameter($name, isset($parameter['def'])? $this->referenceHydrator->hydrate($parameter, new Reference()) : $this->parameterHydrator->hydrate($parameter, new Parameter()));
            }
        }

        if(isset($data['examples'])) {
            foreach ($data['examples'] as $name => $example) {
                $object->addExample($name, isset($example['def'])? $this->referenceHydrator->hydrate($example, new Reference()) : $this->exampleHydrator->hydrate($example, new Example()));
            }
        }

        if(isset($data['requestBodies'])) {
            foreach ($data['requestBodies'] as $name => $requestBody) {
                $object->addRequestBody($name, isset($requestBody['def'])? $this->referenceHydrator->hydrate($requestBody, new Reference()) : $this->requestBodyHydrator->hydrate($requestBody, new RequestBody()));
            }
        }

        if(isset($data['headers'])) {
            foreach ($data['headers'] as $name => $header) {
                $object->addHeader($name, isset($header['def'])? $this->referenceHydrator->hydrate($header, new Reference()) : $this->headerHydrator->hydrate($header, new Header()));
            }
        }

        if(isset($data['securitySchemes'])) {
            foreach ($data['securitySchemes'] as $name => $securityScheme) {
                $object->addSecurityScheme($name, isset($securityScheme['def'])? $this->referenceHydrator->hydrate($securityScheme, new Reference()) : $this->securitySchemeHydrator->hydrate($securityScheme, new SecurityScheme()));
            }
        }

        if(isset($data['links'])) {
            foreach ($data['links'] as $name => $link) {
                $object->addLink($name, isset($link['def'])? $this->referenceHydrator->hydrate($link, new Reference()) : $this->linkHydrator->hydrate($link, new Link()));
            }
        }

        if(isset($data['callbacks'])) {
            foreach ($data['callbacks'] as $name => $callback) {
                $object->addCallback($name, isset($callback['def'])? $this->referenceHydrator->hydrate($callback, new Reference()) : $this->callbackHydrator->hydrate($callback, new Callback()));
            }
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Components $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [];
    }
}
