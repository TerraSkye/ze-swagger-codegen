<?php
declare(strict_types=1);

namespace Swagger\V30\Schema;

class Tag
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var ExternalDocumentation
     */
    protected $externalDocs;

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
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;
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
     * @return static
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return ExternalDocumentation
     */
    public function getExternalDocs(): ExternalDocumentation
    {
        return $this->externalDocs;
    }

    /**
     * @param ExternalDocumentation $externalDocs
     *
     * @return static
     */
    public function setExternalDocs(ExternalDocumentation $externalDocs)
    {
        $this->externalDocs = $externalDocs;
        return $this;
    }
}
