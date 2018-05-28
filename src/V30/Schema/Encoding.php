<?php
declare(strict_types=1);

namespace Swagger\V30\Schema;

class Encoding
{
    /**
     * @var string
     */
    protected $contentType;

    /**
     * @var Header[]|Reference[]
     */
    protected $headers = [];

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
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     *
     * @return self
     */
    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @return Header[]|Reference[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param Header[]|Reference[] $headers
     *
     * @return self
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param  Header|Reference $header
     *
     * @return self
     */
    public function addHeader($header): self
    {
        if ($header instanceof Header || $header instanceof Reference) {
            array_push($this->headers, $header);
        }
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
}
