<?php
declare(strict_types=1);

namespace Swagger\V30\Schema;

class RequestBody
{
    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var MediaType[]
     */
    protected $content = [];

    /**
     * @var bool
     */
    protected $required;

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
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
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

    /**
     * @param string $mediaType
     * @param  MediaType $content
     * @return self
     */
    public function addContent(string $mediaType, MediaType $content): self
    {
        $this->content[$mediaType] = $content;

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
     * @param bool|null $required
     *
     * @return self
     */
    public function setRequired(?bool $required): self
    {
        $this->required = is_null($required) ? false : $required;
        return $this;
    }
}
