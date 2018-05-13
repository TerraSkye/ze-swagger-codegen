<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

class OAuthFlows
{
    /**
     * @var OAuthFlow
     */
    protected $implicit;

    /**
     * @var OAuthFlow
     */
    protected $password;

    /**
     * @var OAuthFlow
     */
    protected $clientCredentials;

    /**
     * @var OAuthFlow
     */
    protected $authorizationCode;

    /**
     * @return OAuthFlow
     */
    public function getImplicit(): OAuthFlow
    {
        return $this->implicit;
    }

    /**
     * @param OAuthFlow $implicit
     *
     * @return self
     */
    public function setImplicit(OAuthFlow $implicit): self
    {
        $this->implicit = $implicit;
        return $this;
    }

    /**
     * @return OAuthFlow
     */
    public function getPassword(): OAuthFlow
    {
        return $this->password;
    }

    /**
     * @param OAuthFlow $password
     *
     * @return self
     */
    public function setPassword(OAuthFlow $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return OAuthFlow
     */
    public function getClientCredentials(): OAuthFlow
    {
        return $this->clientCredentials;
    }

    /**
     * @param OAuthFlow $clientCredentials
     *
     * @return self
     */
    public function setClientCredentials(OAuthFlow $clientCredentials): self
    {
        $this->clientCredentials = $clientCredentials;
        return $this;
    }

    /**
     * @return OAuthFlow
     */
    public function getAuthorizationCode(): OAuthFlow
    {
        return $this->authorizationCode;
    }

    /**
     * @param OAuthFlow $authorizationCode
     *
     * @return self
     */
    public function setAuthorizationCode(OAuthFlow $authorizationCode): self
    {
        $this->authorizationCode = $authorizationCode;
        return $this;
    }
}
