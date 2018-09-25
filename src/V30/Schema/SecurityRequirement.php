<?php
declare(strict_types=1);

namespace Swagger\V30\Schema;

class SecurityRequirement
{
    /**
     * @var string[]
     */
    protected $fields = [];

    /**
     * @param  string $name
     * @param  mixed $value
     *
     * @return self
     */
    public function setField(string $name, $value): self
    {
        $this->fields[$name] = $value;

        return $this;
    }

    /**
     * @param  string $name
     *
     * @return mixed
     */
    public function getField(string $name)
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
