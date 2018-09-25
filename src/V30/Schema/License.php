<?php
declare(strict_types=1);

namespace Swagger\V30\Schema;

class License
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $url;

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
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     *
     * @return self
     */
    public function setUrl(string $url = null): self
    {
        $this->url = $url;
        return $this;
    }
}
