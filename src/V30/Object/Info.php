<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

class Info
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $termsOfService;

    /**
     * @var Contact|null
     */
    protected $contact;

    /**
     * @var License|null
     */
    protected $license;

    /**
     * @var string
     */
    protected $version;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
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

    /**
     * @return string|null
     */
    public function getTermsOfService(): ?string
    {
        return $this->termsOfService;
    }

    /**
     * @param string|null $termsOfService
     *
     * @return self
     */
    public function setTermsOfService(string $termsOfService = null): self
    {
        $this->termsOfService = $termsOfService;
        return $this;
    }

    /**
     * @return Contact|null
     */
    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    /**
     * @param Contact|null $contact
     *
     * @return self
     */
    public function setContact(Contact $contact = null): self
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @return License|null
     */
    public function getLicense(): ?License
    {
        return $this->license;
    }

    /**
     * @param License|null $license
     *
     * @return self
     */
    public function setLicense(License $license = null): self
    {
        $this->license = $license;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     *
     * @return self
     */
    public function setVersion(string $version): self
    {
        $this->version = $version;
        return $this;
    }
}
