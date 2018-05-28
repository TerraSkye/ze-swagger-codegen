<?php
declare(strict_types=1);

namespace Swagger\V30\Schema;

class OAuthFlow
{
    /**
     * @var string
     */
    protected $authorizationUrl;

    /**
     * @var string
     */
    protected $tokenUrl;

    /**
     * @var string|null
     */
    protected $refreshUrl;

    /**
     * @var string[]
     */
    protected $scopes = [];

    /**
     * @return string
     */
    public function getAuthorizationUrl(): string
    {
        return $this->authorizationUrl;
    }

    /**
     * @param string $authorizationUrl
     *
     * @return self
     */
    public function setAuthorizationUrl(string $authorizationUrl): self
    {
        $this->authorizationUrl = $authorizationUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenUrl(): string
    {
        return $this->tokenUrl;
    }

    /**
     * @param string $tokenUrl
     *
     * @return self
     */
    public function setTokenUrl(string $tokenUrl): self
    {
        $this->tokenUrl = $tokenUrl;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRefreshUrl(): ?string
    {
        return $this->refreshUrl;
    }

    /**
     * @param string|null $refreshUrl
     *
     * @return self
     */
    public function setRefreshUrl(?string $refreshUrl): self
    {
        $this->refreshUrl = $refreshUrl;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @param string[] $scopes
     *
     * @return self
     */
    public function setScopes(array $scopes): self
    {
        $this->scopes = $scopes;
        return $this;
    }
}
