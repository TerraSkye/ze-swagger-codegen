<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

/**
 * This is the root document object of the OpenAPI document.
 * ===
 */
class Document
{
    /**
     * The semantic version number of the OpenAPI Specification version that the OpenAPI document uses.
     * ---
     * @var string
     */
    protected $openapi;

    /**
     * Provides metadata about the API. The metadata MAY be used by tooling as required.
     * ---
     * @var Info
     */
    protected $info;

    /**
     * @var Server[]
     */
    protected $servers = [];

    /**
     * The available paths and operations for the API.
     * ---
     * @var PathItem[]
     */
    protected $paths = [];

    /**
     * An element to hold various schemas for the specification.
     * ---
     * @var Components|null
     */
    protected $components;

    /**
     * @var SecurityRequirement[]
     */
    protected $security = [];

    /**
     * @var Tag[]
     */
    protected $tags = [];

    /**
     * Additional external documentation.
     * ---
     * @var ExternalDocumentation|null
     */
    protected $externalDocs;

    /**
     * Get the semantic version number of the OpenAPI Specification version.
     * ---
     * @return string
     */
    public function getOpenapi(): string
    {
        return $this->openapi;
    }

    /**
     * @param string $openapi
     *
     * @return self
     */
    public function setOpenapi(string $openapi): self
    {
        $this->openapi = $openapi;
        return $this;
    }

    /**
     * @return Info
     */
    public function getInfo(): Info
    {
        return $this->info;
    }

    /**
     * @param Info $info
     *
     * @return self
     */
    public function setInfo(Info $info): self
    {
        $this->info = $info;
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
     * Get the available paths and operations for the API.
     * ---
     * @return PathItem[]
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param PathItem[] $paths
     *
     * @return self
     */
    public function setPaths(array $paths): self
    {
        $this->paths = $paths;
        return $this;
    }

    /**
     * @param string   $path
     * @param PathItem $pathItem
     *
     * @return self
     */
    public function addPath(string $path, PathItem $pathItem): self
    {
        $this->paths[$path] = $pathItem;

        return $this;
    }

    /**
     * @return Components|null
     */
    public function getComponents(): ?Components
    {
        return $this->components;
    }

    /**
     * @param Components|null $components
     *
     * @return self
     */
    public function setComponents(Components $components = null): self
    {
        $this->components = $components;
        return $this;
    }

    /**
     * @return SecurityRequirement[]
     */
    public function getSecurity(): array
    {
        return $this->security;
    }

    /**
     * @param SecurityRequirement[] $security
     *
     * @return self
     */
    public function setSecurity(array $security): self
    {
        $this->security = $security;
        return $this;
    }

    /**
     * @param  SecurityRequirement $security
     * @return self
     */
    public function addSecurity(SecurityRequirement $security): self
    {
        array_push($this->security, $security);

        return $this;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param Tag[] $tags
     *
     * @return self
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @param  Tag $tag
     * @return self
     */
    public function addTag(Tag $tag): self
    {
        array_push($this->tags, $tag);

        return $this;
    }

    /**
     * @return ExternalDocumentation|null
     */
    public function getExternalDocs(): ?ExternalDocumentation
    {
        return $this->externalDocs;
    }

    /**
     * @param ExternalDocumentation|null $externalDocs
     *
     * @return self
     */
    public function setExternalDocs(ExternalDocumentation $externalDocs = null): self
    {
        $this->externalDocs = $externalDocs;
        return $this;
    }
}
