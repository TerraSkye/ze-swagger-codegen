<?php
declare(strict_types=1);

namespace Swagger\V30\Schema;

class Link
{
    /**
     * @var string
     */
    protected $operationRef;

    /**
     * @var string|null
     */
    protected $operationId;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var mixed
     */
    protected $requestBody;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var Server
     */
    protected $server;

    /**
     * @return string
     */
    public function getOperationRef(): string
    {
        return $this->operationRef;
    }

    /**
     * @param string $operationRef
     *
     * @return self
     */
    public function setOperationRef(string $operationRef): self
    {
        $this->operationRef = $operationRef;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOperationId(): ?string
    {
        return $this->operationId;
    }

    /**
     * @param string|null $operationId
     *
     * @return self
     */
    public function setOperationId(?string $operationId): self
    {
        $this->operationId = $operationId;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     *
     * @return self
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
     * @param mixed $requestBody
     *
     * @return self
     */
    public function setRequestBody($requestBody): self
    {
        $this->requestBody = $requestBody;
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
     * @param string|null $description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }

    /**
     * @param Server $server
     *
     * @return self
     */
    public function setServer(Server $server): self
    {
        $this->server = $server;
        return $this;
    }
}
