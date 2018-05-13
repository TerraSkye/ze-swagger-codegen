<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

class Parameter
{
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
    protected $description;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var bool
     */
    protected $deprecated;

    /**
     * @var bool
     */
    protected $allowEmptyValue;

    /**
     * @var string
     */
    protected $style;

    /**
     * @var bool
     */
    protected $explode;

    /**
     * @var bool
     */
    protected $allowReserved;

    /**
     * @var Reference|
     */
    protected $schema;

    /**
     * @var mixed
     */
    protected $example;

    /**
     * @var Reference[]|Example[]
     */
    protected $examples = [];

    /**
     * @var MediaType[]
     */
    protected $content;

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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     *
     * @return self
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDeprecated(): bool
    {
        return $this->deprecated;
    }

    /**
     * @param bool $deprecated
     *
     * @return self
     */
    public function setDeprecated(bool $deprecated): self
    {
        $this->deprecated = $deprecated;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowEmptyValue(): bool
    {
        return $this->allowEmptyValue;
    }

    /**
     * @param bool $allowEmptyValue
     *
     * @return self
     */
    public function setAllowEmptyValue(bool $allowEmptyValue): self
    {
        $this->allowEmptyValue = $allowEmptyValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getStyle(): string
    {
        return $this->style;
    }

    /**
     * @param string $style
     *
     * @return self
     */
    public function setStyle(string $style): self
    {
        $this->style = $style;
        return $this;
    }

    /**
     * @return bool
     */
    public function getExplode(): bool
    {
        return $this->explode;
    }

    /**
     * @param bool $explode
     *
     * @return self
     */
    public function setExplode(bool $explode): self
    {
        $this->explode = $explode;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowReserved(): bool
    {
        return $this->allowReserved;
    }

    /**
     * @param bool $allowReserved
     *
     * @return self
     */
    public function setAllowReserved(bool $allowReserved): self
    {
        $this->allowReserved = $allowReserved;
        return $this;
    }

    /**
     * @return Schema|Reference
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param Schema|Reference $schema
     *
     * @return self
     */
    public function setSchema($schema): self
    {
        if ($schema instanceof Schema || $schema instanceof Reference) {
            $this->schema = $schema;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExample()
    {
        return $this->example;
    }

    /**
     * @param mixed $example
     *
     * @return self
     */
    public function setExample($example): self
    {
        $this->example = $example;
        return $this;
    }

    /**
     * @return Reference[]|Example[]
     */
    public function getExamples(): array
    {
        return $this->examples;
    }

    /**
     * @param Reference[]|Example[] $examples
     *
     * @return self
     */
    public function setExamples(array $examples): self
    {
        $this->examples = $examples;
        return $this;
    }

    /**
     * @param Reference|Example $example
     */
    public function addExample($example)
    {
        if($example instanceof Reference || $example instanceof Example) {
            array_push($this->examples, $example);
        }
    }

    /**
     * @return MediaType[]
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param MediaType[] $content
     *
     * @return self
     */
    public function setContent(array $content): self
    {
        $this->content = $content;
        return $this;
    }
}
