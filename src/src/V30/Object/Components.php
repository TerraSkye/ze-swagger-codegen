<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

class Components
{
    /**
     * @var Schema[]|Reference[]
     */
    protected $schemas = [];

    /**
     * @var Response[]|Reference[]
     */
    protected $responses = [];

    /**
     * @var Parameter[]|Reference[]
     */
    protected $parameters = [];

    /**
     * @var Example[]|Reference[]
     */
    protected $examples = [];

    /**
     * @var RequestBody[]|Reference[]
     */
    protected $requestBodies = [];

    /**
     * @var Header[]|Reference[]
     */
    protected $headers = [];

    /**
     * @var SecurityScheme[]|Reference[]
     */
    protected $securitySchemes = [];

    /**
     * @var Link[]|Reference[]
     */
    protected $links = [];

    /**
     * @var Callback[]|Reference[]
     */
    protected $callbacks = [];

    /**
     * @return Schema[]
     */
    public function getSchemas(): array
    {
        return $this->schemas;
    }

    /**
     * @param Schema[]|Reference[] $schemas
     *
     * @return self
     */
    public function setSchemas(array $schemas)
    {
        $this->schemas = $schemas;
        return $this;
    }

    /**
     * @param string $name
     * @param Schema|Reference $schema
     *
     * @return self
     */
    public function addSchema(string $name, $schema): self
    {
        if ($schema instanceof Schema || $schema instanceof Reference) {
            $this->schemas[$name] = $schema;
        }

        return $this;
    }

    /**
     * @return Response[]|Reference[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    /**
     * @param Response[]|Reference[] $responses
     *
     * @return self
     */
    public function setResponses(array $responses)
    {
        $this->responses = $responses;
        return $this;
    }

    /**
     * @param string $name
     * @param Response|Reference $response
     *
     * @return self
     */
    public function addResponse(string $name, $response): self
    {
        if ($response instanceof Response || $response instanceof Reference) {
            $this->responses[$name] = $response;
        }

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
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param string $name
     * @param Parameter|Reference $parameter
     *
     * @return self
     */
    public function addParameter(string $name, $parameter): self
    {
        if ($parameter instanceof Parameter || $parameter instanceof Reference) {
            $this->parameters[$name] = $parameter;
        }

        return $this;
    }

    /**
     * @return Example[]|Reference[]
     */
    public function getExamples(): array
    {
        return $this->examples;
    }

    /**
     * @param Example[]|Reference[] $examples
     *
     * @return self
     */
    public function setExamples(array $examples)
    {
        $this->examples = $examples;
        return $this;
    }

    /**
     * @param string $name
     * @param Example|Reference $example
     *
     * @return self
     */
    public function addExample(string $name, $example): self
    {
        if ($example instanceof Example || $example instanceof Reference) {
            $this->examples[$name] = $example;
        }

        return $this;
    }

    /**
     * @return RequestBody[]|Reference[]
     */
    public function getRequestBodies(): array
    {
        return $this->requestBodies;
    }

    /**
     * @param RequestBody[]|Reference[] $requestBodies
     *
     * @return self
     */
    public function setRequestBodies(array $requestBodies)
    {
        $this->requestBodies = $requestBodies;
        return $this;
    }

    /**
     * @param string $name
     * @param RequestBody|Reference $requestBody
     *
     * @return self
     */
    public function addRequestBody(string $name, $requestBody): self
    {
        if ($requestBody instanceof RequestBody || $requestBody instanceof Reference) {
            $this->requestBodies[$name] = $requestBody;
        }

        return $this;
    }

    /**
     * @return Header[]|Reference[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param Header[]|Reference[] $headers
     *
     * @return self
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param string $name
     * @param Header|Reference $header
     *
     * @return self
     */
    public function addHeader(string $name, $header): self
    {
        if ($header instanceof Header || $header instanceof Reference) {
            $this->headers[$name] = $header;
        }

        return $this;
    }

    /**
     * @return SecurityScheme[]|Reference[]
     */
    public function getSecuritySchemes(): array
    {
        return $this->securitySchemes;
    }

    /**
     * @param SecurityScheme[]|Reference[] $securitySchemes
     *
     * @return self
     */
    public function setSecuritySchemes(array $securitySchemes)
    {
        $this->securitySchemes = $securitySchemes;
        return $this;
    }

    /**
     * @param string $name
     * @param SecurityScheme|Reference $securityScheme
     *
     * @return self
     */
    public function addSecurityScheme(string $name, $securityScheme): self
    {
        if ($securityScheme instanceof SecurityScheme || $securityScheme instanceof Reference) {
            $this->securitySchemes[$name] = $securityScheme;
        }

        return $this;
    }

    /**
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param Link[] $links
     *
     * @return self
     */
    public function setLinks(array $links)
    {
        $this->links = $links;
        return $this;
    }

    /**
     * @param string $name
     * @param Link|Reference $link
     *
     * @return self
     */
    public function addLink(string $name, $link): self
    {
        if ($link instanceof Link || $link instanceof Reference) {
            $this->links[$name] = $link;
        }

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
    public function setCallbacks(array $callbacks)
    {
        $this->callbacks = $callbacks;
        return $this;
    }

    /**
     * @param string $name
     * @param Callback|Reference $callback
     *
     * @return self
     */
    public function addCallback(string $name, $callback): self
    {
        if ($callback instanceof Callback || $callback instanceof Reference) {
            $this->callbacks[$name] = $callback;
        }

        return $this;
    }
}
