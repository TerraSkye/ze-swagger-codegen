<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

class Schema
{
    /**
     * @var string[]
     */
    protected const TYPES = [
        'array',
        'boolean',
        'integer',
        'null',
        'number',
        'object',
        'string',
        'reference'
    ];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type = 'object';

    /**
     * @var string
     */
    protected $format;
    /**
     * @var Schema[]
     */
    protected $properties = [];

    /**
     * @var Schema|Reference
     */
    protected $items;

    /**
     * @var string[]
     */
    protected $required = [];

    /**
     * Constructor
     * ---
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        if ($name) {
            $this->setName($name);
        }
    }

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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        if (!in_array($type, self::TYPES)) {
            throw new \Exception('Invalid Schema type');
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhpType(): string
    {
        switch($this->type) {
            case 'array':
                return 'array';
                break;
            case 'boolean':
                return 'bool';
                break;
            case 'integer':
                return 'int';
                break;
            case 'null':
                return 'null';
                break;
            case 'number':
                return 'float';
                break;
            case 'object':
                return 'object';
                break;
            case 'string':
                return 'string';
                break;
            case 'reference':
                break;
        }
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return self
     */
    public function setFormat(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return Schema[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param Schema[] $properties
     *
     * @return self
     */
    public function setProperties(array $properties): self
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @param string $name
     * @param Schema $property
     *
     * @return self
     */
    public function setProperty(string $name, Schema $property): self
    {
        $this->properties[$name] = $property;

        return $this;
    }

    /**
     * @return Schema|Reference
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Schema|Reference $items
     *
     * @return self
     */
    public function setItems($items): self
    {
        if ($items instanceof Schema || $items instanceof Reference) {
            $this->items = $items;
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->type === 'array';
    }

    /**
     * @return bool
     */
    public function isBoolean(): bool
    {
        return $this->type === 'boolean';
    }

    /**
     * @return bool
     */
    public function isInteger(): bool
    {
        return $this->type === 'integer';
    }

    /**
     * @return bool
     */
    public function isNull(): bool
    {
        return $this->type === 'null';
    }

    /**
     * @return bool
     */
    public function isNumber(): bool
    {
        return $this->type === 'number';
    }

    /**
     * @return bool
     */
    public function isObject(): bool
    {
        return $this->type === 'object';
    }

    /**
     * @return bool
     */
    public function isString(): bool
    {
        return $this->type === 'string';
    }

    /**
     * @return bool
     */
    public function isReference(): bool
    {
        return $this->type === 'reference';
    }

    /**
     * @return string[]
     */
    public function getRequired(): array
    {
        return $this->required;
    }

    /**
     * @param string[] $required
     *
     * @return self
     */
    public function setRequired(array $required): self
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @param string $required
     *
     * @return self
     */
    public function addRequired(string $required): self
    {
        array_push($this->required, $required);

        return $this;
    }

    /**
     * @param  string  $property
     *
     * @return bool
     */
    public function isRequired(string $property): bool
    {
        return in_array($property, $this->required);
    }
}
