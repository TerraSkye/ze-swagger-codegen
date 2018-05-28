<?php
declare(strict_types=1);

namespace Swagger\V30\Schema;

class SecurityScheme
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $in;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string|null
     */
    protected $bearerFormat;

    /**
     * @var OAuthFlows
     */
    protected $flows;

    /**
     * @var string
     */
    protected $openIdConnectUrl;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getIn(): string
    {
        return $this->in;
    }

    /**
     * @param string $in
     *
     * @return self
     */
    public function setIn(string $in): self
    {
        $this->in = $in;
        return $this;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     *
     * @return self
     */
    public function setScheme(string $scheme): self
    {
        $this->scheme = $scheme;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBearerFormat(): ?string
    {
        return $this->bearerFormat;
    }

    /**
     * @param string|null $bearerFormat
     *
     * @return self
     */
    public function setBearerFormat(?string $bearerFormat): self
    {
        $this->bearerFormat = $bearerFormat;
        return $this;
    }

    /**
     * @return OAuthFlows
     */
    public function getFlows(): OAuthFlows
    {
        return $this->flows;
    }

    /**
     * @param OAuthFlows $flows
     *
     * @return self
     */
    public function setFlows(OAuthFlows $flows): self
    {
        $this->flows = $flows;
        return $this;
    }

    /**
     * @return string
     */
    public function getOpenIdConnectUrl(): string
    {
        return $this->openIdConnectUrl;
    }

    /**
     * @param string $openIdConnectUrl
     *
     * @return self
     */
    public function setOpenIdConnectUrl(string $openIdConnectUrl): self
    {
        $this->openIdConnectUrl = $openIdConnectUrl;
        return $this;
    }
}
