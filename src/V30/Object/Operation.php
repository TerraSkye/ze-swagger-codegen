<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

class Operation
{
    /**
     * @var string[]|null
     */
    protected $tags = [];

    /**
     * @var string|null
     */
    protected $summary = null;

    /**
     * @var string|null
     */
    protected $description = null;

    /**
     * @var ExternalDocumentation|null
     */
    protected $externalDocs;

    /**
     * @var string
     */
    protected $operationId;

    /**
     * @var Parameter[]|Reference[]
     */
    protected $parameters = [];

    /**
     * @var RequestBody|null
     */
    protected $requestBody;

    /**
     * @var Responses
     */
    protected $responses;

    /**
     * @var Callback[]|Reference[]
     */
    protected $callbacks = [];

    /**
     * @var bool
     */
    protected $deprecated = false;

    /**
     * @var SecurityRequirement
     */
    protected $security;

    /**
     * @var Server[]
     */
    protected $servers = [];

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param string[] $tags
     *
     * @return self
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     *
     * @return self
     */
    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return ExternalDocumentation
     */
    public function getExternalDocs(): ExternalDocumentation
    {
        return $this->externalDocs;
    }

    /**
     * @param ExternalDocumentation $externalDocs
     *
     * @return self
     */
    public function setExternalDocs(ExternalDocumentation $externalDocs): self
    {
        $this->externalDocs = $externalDocs;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperationId(): string
    {
        return $this->operationId;
    }

    /**
     * @param string $operationId
     *
     * @return self
     */
    public function setOperationId(string $operationId): self
    {
        $this->operationId = $operationId;
        return $this;
    }

    /**
     * @return Parameter[]|Reference[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param Parameter[]|Reference[] $parameters
     *
     * @return self
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param Parameter|Reference $parameter
     *
     * @return self
     */
    public function addParameter($parameter): self
    {
        if ($parameter instanceof Parameter || $parameter instanceof Reference) {
            array_push($this->parameters, $parameter);
        }

        return $this;
    }

    /**
     * @return RequestBody|null
     */
    public function getRequestBody(): ?RequestBody
    {
        return $this->requestBody;
    }

    /**
     * @param RequestBody|null $requestBody
     *
     * @return self
     */
    public function setRequestBody(RequestBody $requestBody = null): self
    {
        $this->requestBody = $requestBody;
        return $this;
    }

    /**
     * @return Responses
     */
    public function getResponses(): Responses
    {
        return $this->responses;
    }

    /**
     * @param Responses $responses
     *
     * @return self
     */
    public function setResponses(Responses $responses): self
    {
        $this->responses = $responses;
        return $this;
    }

    /**
     * @return Callback[]
     */
    public function getCallbacks(): array
    {
        return $this->callbacks;
    }

    /**
     * @param Callback[] $callbacks
     *
     * @return self
     */
    public function setCallbacks(array $callbacks): self
    {
        $this->callbacks = $callbacks;
        return $this;
    }

    /**
     * @param string $name
     * @param Callback $callback
     *
     * @return self
     */
    public function addCallback(string $name, Callback $callback): self
    {
        $this->callbacks[$name] = $callback;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDeprecated(): bool
    {
        return $this->deprecated;
    }

    /**
     * @param bool $deprecated
     *
     * @return self
     */
    public function setDeprecated(bool $deprecated): self
    {
        $this->deprecated = $deprecated;
        return $this;
    }

    /**
     * @return SecurityRequirement
     */
    public function getSecurity(): SecurityRequirement
    {
        return $this->security;
    }

    /**
     * @param SecurityRequirement $security
     *
     * @return self
     */
    public function setSecurity(SecurityRequirement $security): self
    {
        $this->security = $security;
        return $this;
    }

    /**
     * @return Server[]
     */
    public function getServers(): array
    {
        return $this->servers;
    }

    /**
     * @param Server[] $servers
     *
     * @return self
     */
    public function setServers(array $servers): self
    {
        $this->servers = $servers;
        return $this;
    }

    /**
     * @param Server $server
     *
     * @return self
     */
    public function addServer(Server $server): self
    {
        array_push($this->servers, $server);

        return $this;
    }
}
