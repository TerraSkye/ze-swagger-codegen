<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

class SecurityRequirement
{
    /**
     * @var string[]
     */
    protected $fields = [];

    /**
     * @param  string $name
     * @param  string $value
     *
     * @return self
     */
    public function setField(string $name, string $value): self
    {
        $this->fields[$name] = $value;

        return $this;
    }

    /**
     * @param  string $name
     *
     * @return string
     */
    public function getField(string $name): string
    {
        return $this->fields[$name];
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
