<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

class Reference
{
    /**
     * @var string
     */
    protected $ref;

    /**
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @param string $ref
     *
     * @return self
     */
    public function setRef(string $ref): self
    {
        $this->ref = $ref;
        return $this;
    }
}
