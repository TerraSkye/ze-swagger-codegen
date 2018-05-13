<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

class Responses
{
    /**
     * @var Response| Reference
     */
    protected $default;

    /**
     * @var Response[]|Reference[]
     */
    protected $statusResponses = [];

    /**
     * @return Response|Reference
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param Response|Reference $default
     *
     * @return self
     */
    public function setDefault($default): self
    {
        if($default instanceof Response || $default instanceof Reference) {
            $this->default = $default;
        }
        return $this;
    }

    /**
     * @param  string $statusCode
     * @param  Response|Reference $response
     *
     * @return self
     */
    public function setStatusResponse(string $statusCode, $response): self
    {
        if($response instanceof Response || $response instanceof Reference) {
            $this->statusResponses[$statusCode] = $response;
        }

        return $this;
    }

    /**
     * @param  string $name
     *
     * @return string
     */
    public function getStatusResponse(string $statusCode): string
    {
        return $this->statusResponses[$statusCode];
    }

    /**
     * @return array
     */
    public function getStatusResponses(): array
    {
        return $this->statusResponses;
    }
}
