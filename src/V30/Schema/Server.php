<?php
declare(strict_types=1);

namespace Swagger\V30\Schema;

class Server
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var ServerVariable[]
     */
    protected $variables = [];

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
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
    public function setDescription(string $description = null): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return ServerVariable[]
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param ServerVariable[] $variables
     *
     * @return self
     */
    public function setVariables(array $variables): self
    {
        $this->variables = $variables;
        return $this;
    }

    /**
     * @param ServerVariable $variable
     */
    public function addVariable(ServerVariable $variable)
    {
        array_push($this->variables, $variable);
    }
}
