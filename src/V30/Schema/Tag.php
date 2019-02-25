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
     * @return ExternalDocumentation
     */
    public function getExternalDocs(): ExternalDocumentation
    {
        return $this->externalDocs;
    }

    /**
     * @param ExternalDocumentation $externalDocs
     *
     * @return self
     */
    public function setExternalDocs(ExternalDocumentation $externalDocs): self
    {
        $this->externalDocs = $externalDocs;
        return $this;
    }
}
