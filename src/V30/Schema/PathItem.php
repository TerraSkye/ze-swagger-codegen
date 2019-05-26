<?php
declare(strict_types=1);

namespace Swagger\V30\Schema;

use Fig\Http\Message\RequestMethodInterface;

class PathItem
{
    /**
     * @var string
     */
    protected $ref;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var Operation
     */
    protected $get;

    /**
     * @var Operation
     */
    protected $put;

    /**
     * @var Operation
     */
    protected $post;

    /**
     * @var Operation
     */
    protected $delete;

    /**
     * @var Operation
     */
    protected $options;

    /**
     * @var Operation
     */
    protected $head;

    /**
     * @var Operation
     */
    protected $patch;

    /**
     * @var Operation
     */
    protected $trace;

    /**
     * @var Server[]
     */
    protected $servers = [];

    /**
     * @var Parameter[]|Reference[]
     */
    protected $parameters = [];

    /**
     * @var Operation[]
     */
    protected $operations = [];

    /**
     * @var string|null
     */
    protected $xHandler;

    /**
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @param string $ref
     *
     * @return self
     */
    public function setRef(string $ref): self
    {
        $this->ref = $ref;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     *
     * @return self
     */
    public function setSummary(string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Operation
     */
    public function getGet(): Operation
    {
        return $this->get;
    }

    /**
     * @param Operation $get
     *
     * @return self
     */
    public function setGet(Operation $get): self
    {
        $this->operations[RequestMethodInterface::METHOD_GET] = $get;

        $this->get = $get;
        return $this;
    }

    /**
     * @return Operation
     */
    public function getPut(): Operation
    {
        return $this->put;
    }

    /**
     * @param Operation $put
     *
     * @return self
     */
    public function setPut(Operation $put): self
    {
        $this->operations[RequestMethodInterface::METHOD_PUT] = $put;

        $this->put = $put;
        return $this;
    }

    /**
     * @return Operation
     */
    public function getPost(): Operation
    {
        return $this->post;
    }

    /**
     * @param Operation $post
     *
     * @return self
     */
    public function setPost(Operation $post): self
    {
        $this->operations[RequestMethodInterface::METHOD_POST] = $post;

        $this->post = $post;
        return $this;
    }

    /**
     * @return Operation
     */
    public function getDelete(): Operation
    {
        return $this->delete;
    }

    /**
     * @param Operation $delete
     *
     * @return self
     */
    public function setDelete(Operation $delete): self
    {
        $this->operations[RequestMethodInterface::METHOD_DELETE] = $delete;

        $this->delete = $delete;
        return $this;
    }

    /**
     * @return Operation
     */
    public function getOptions(): Operation
    {
        return $this->options;
    }

    /**
     * @param Operation $options
     *
     * @return self
     */
    public function setOptions(Operation $options): self
    {
        $this->operations[RequestMethodInterface::METHOD_OPTIONS] = $options;

        $this->options = $options;
        return $this;
    }

    /**
     * @return Operation
     */
    public function getHead(): Operation
    {
        return $this->head;
    }

    /**
     * @param Operation $head
     *
     * @return self
     */
    public function setHead(Operation $head): self
    {
        $this->operations[RequestMethodInterface::METHOD_HEAD] = $head;

        $this->head = $head;
        return $this;
    }

    /**
     * @return Operation
     */
    public function getPatch(): Operation
    {
        return $this->patch;
    }

    /**
     * @param Operation $patch
     *
     * @return self
     */
    public function setPatch(Operation $patch): self
    {
        $this->operations[RequestMethodInterface::METHOD_PATCH] = $patch;

        $this->patch = $patch;
        return $this;
    }

    /**
     * @return Operation
     */
    public function getTrace(): Operation
    {
        return $this->trace;
    }

    /**
     * @param Operation $trace
     *
     * @return self
     */
    public function setTrace(Operation $trace): self
    {
        $this->operations[RequestMethodInterface::METHOD_TRACE] = $trace;

        $this->trace = $trace;
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
     * @param Parameter $parameter
     *
     * @return self
     */
    public function addParameter(Parameter $parameter): self
    {
        array_push($this->parameters, $parameter);

        return $this;
    }

    /**
     * @return array
     */
    public function getOperations(): array
    {
        return $this->operations;
    }

    /**
     * @return string|null
     */
    public function getXHandler(): ?string
    {
        return $this->xHandler;
    }

    /**
     * @param string|null $xhandler
     * @return PathItem
     */
    public function setXHandler(?string $xHandler): PathItem
    {
        $this->xHandler = $xHandler;
        return $this;
    }
}
