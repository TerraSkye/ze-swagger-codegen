<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

class Callback
{
    /**
     * @var PathItem[]
     */
    protected $expressions = [];

    /**
     * @param  string $name
     * @param  PathItem $pathItem
     *
     * @return self
     */
    public function setExpression(string $name, PathItem $pathItem): self
    {
        $this->expressions[$name] = $pathItem;

        return $this;
    }

    /**
     * @param  string $name
     *
     * @return PathItem
     */
    public function getExpression(string $name): PathItem
    {
        return $this->expressions[$name];
    }

    /**
     * @return array
     */
    public function getExpressions(): array
    {
        return $this->expressions;
    }
}
