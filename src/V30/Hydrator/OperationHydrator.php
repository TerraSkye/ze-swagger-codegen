<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Laminas\Hydrator\HydratorInterface;
use Swagger\V30\Schema\Server;
use Swagger\V30\Schema\Callback;
use Swagger\V30\Schema\Parameter;
use Swagger\V30\Schema\Reference;
use Swagger\V30\Schema\Responses;
use Swagger\V30\Schema\RequestBody;
use Swagger\V30\Schema\SecurityRequirement;
use Swagger\V30\Schema\ExternalDocumentation;

class OperationHydrator implements HydratorInterface
{
    /**
     * @var ServerHydrator
     */
    protected $serverHydrator;

    /**
     * @var ParameterHydrator
     */
    protected $parameterHydrator;

    /**
     * @var ExternalDocumentationHydrator
     */
    protected $externalDocsHydrator;

    /**
     * @var RequestBodyHydrator
     */
    protected $requestBodyHydrator;

    /**
     * @var SecurityRequirementHydrator
     */
    protected $securityReqHydrator;

    /**
     * @var ReferenceHydrator
     */
    protected $referenceHydrator;

    /**
     * @var CallbackHydrator
     */
    protected $callbackHydrator;

    /**
     * @var ResponsesHydrator
     */
    protected $responsesHydrator;

    /**
     * @param ServerHydrator                $serverHydrator
     * @param ParameterHydrator             $parameterHydrator
     * @param ExternalDocumentationHydrator $externalDocumentationHydrator
     * @param RequestBodyHydrator           $requestBodyHydrator
     * @param SecurityRequirementHydrator   $securityRequirementHydrator
     * @param ReferenceHydrator             $referenceHydrator
     * @param CallbackHydrator              $callbackHydrator
     * @param ResponsesHydrator             $responsesHydrator
     */
    public function __construct(
        ServerHydrator $serverHydrator,
        ParameterHydrator $parameterHydrator,
        ExternalDocumentationHydrator $externalDocumentationHydrator,
        RequestBodyHydrator $requestBodyHydrator,
        SecurityRequirementHydrator $securityRequirementHydrator,
        ReferenceHydrator $referenceHydrator,
        CallbackHydrator $callbackHydrator,
        ResponsesHydrator $responsesHydrator
    ) {
        $this->serverHydrator = $serverHydrator;
        $this->parameterHydrator = $parameterHydrator;
        $this->externalDocsHydrator = $externalDocumentationHydrator;
        $this->requestBodyHydrator = $requestBodyHydrator;
        $this->securityReqHydrator = $securityRequirementHydrator;
        $this->referenceHydrator = $referenceHydrator;
        $this->callbackHydrator = $callbackHydrator;
        $this->responsesHydrator = $responsesHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\Operation $object
     *
     * @return Schema\Operation
     */
    public function hydrate(array $data, $object)
    {
        $object->setTags($data['tags']);
        $object->setSummary($data['summary']);

        if(isset($data['description'])) {
            $object->setDescription($data['description']);
        }

        if(isset($data['externalDocs'])) {
            $object->setExternalDocs($this->externalDocsHydrator->hydrate($data['externalDocs'], new ExternalDocumentation()));
        }
        $object->setOperationId($data['operationId']);

        if (isset($data['parameters'])) {
            foreach ($data['parameters'] as $parameter) {
                $object->addParameter(isset($parameter['$ref'])? $this->referenceHydrator->hydrate($parameter, new Reference()) : $this->parameterHydrator->hydrate($parameter, new Parameter()));
            }
        }

        if (isset($data['requestBody'])) {
            $object->setRequestBody(isset($data['requestBody']['$ref'])? $this->referenceHydrator->hydrate($data['requestBody'], new Reference()) : $this->requestBodyHydrator->hydrate($data['requestBody'], new RequestBody()));
        }

        $object->setResponses($this->responsesHydrator->hydrate($data['responses'], new Responses()));

        if (isset($data['callbacks'])) {
            foreach ($data['callbacks'] as $name => $callback) {
                $object->addCallback($name, isset($callback['$ref'])? $this->referenceHydrator->hydrate($callback, new Reference()) : $this->callbackHydrator->hydrate($callback, new Callback()));
            }
        }

        if (isset($data['deprecated'])) {
            $object->setDeprecated($data['deprecated']);
        }

        if (isset($data['security'])) {
            foreach ($data['security'] as $securityRequirement) {
                $object->addSecurityRequirement($this->securityReqHydrator->hydrate($securityRequirement, new SecurityRequirement()));
            }
        }

        if (isset($data['servers'])) {
            foreach ($data['servers'] as $server) {
                $object->addServer($this->serverHydrator->hydrate($server, new Server()));
            }
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\Operation $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'tags' => $object->getTags(),
            'summary'  => $object->getSummary(),
            'description'  => $object->getDescription(),
            'externalDocs' => $this->externalDocsHydrator->extract($object->getExternalDocs()),
            'operationId'  => $object->getOperationId(),
            'requestBody' => $object->getRequestBody() instanceof Reference? $this->referenceHydrator->extract($object->getRequestBody()):($object->getRequestBody()? $this->requestBodyHydrator->extract($object->getRequestBody()) : null),
            'responses' => $object->getResponses(),
            'deprecated' => $object->getDeprecated(),
            'security' => []
        ];

        foreach ($object->getParameters() as $parameter) {
            $data['parameters'][] = $parameter instanceof Reference? $this->referenceHydrator->extract($parameter) :$this->parameterHydrator->extract($parameter);
        }

        foreach ($object->getCallbacks() as $name => $callback) {
            $data['callbacks'][$name] = $callback instanceof Reference? $this->referenceHydrator->extract($callback) :$this->callbackHydrator->extract($callback);
        }

        foreach ($object->getServers() as $server) {
            $data['servers'][] = $this->serverHydrator->extract($server);
        }

        foreach ($object->getSecurity() as $securityRequirement) {
            $data['security'][] = $this->securityReqHydrator->extract($securityRequirement);
        }

        return $data;
    }
}
