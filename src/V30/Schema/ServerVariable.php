<?php
declare(strict_types=1);

namespace Swagger\V30\Schema;

class ServerVariable
{
    /**
     * @var string[]
     */
    protected $enum = [];

    /**
     * @var string
     */
    protected $default;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @return string[]
     */
    public function getEnum(): array
    {
        return $this->enum;
    }

    /**
     * @param string[] $enum
     *
     * @return self
     */
    public function setEnum(array $enum): self
    {
        $this->enum = $enum;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefault(): string
    {
        return $this->default;
    }

    /**
     * @param string $default
     *
     * @return self
     */
    public function setDefault(string $default): self
    {
        $this->default = $default;
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
}
