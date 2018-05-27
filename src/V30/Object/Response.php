<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

class Response
{
    /**
     * @var string
     */
    protected $description;

    /**
     * @var Header[]|Reference[]
     */
    protected $headers;

    /**
     * @var MediaType[]|Reference[]
     */
    protected $content;

    /**
     * @var Link[]|Reference[]
     */
    protected $links;

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
     * @param string $name
     * @param Header|Reference $header
     *
     * @return self
     */
    public function addHeader(string $name, $header): self
    {
        if($header instanceof Header || $header instanceof Reference) {
            $this->headers[$name] = $header;
        }
        return $this;
    }

    /**
     * @return MediaType[]|Reference[]
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param MediaType[]|Reference[] $content
     *
     * @return self
     */
    public function setContent(array $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param string $name
     * @param MediaType|Reference $content
     *
     * @return self
     */
    public function addContent(string $name, $content): self
    {
        if($content instanceof MediaType || $content instanceof Reference) {
            $this->content[$name] = $content;
        }
        return $this;
    }

    /**
     * @return Link[]|Reference[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param Link[]|Reference[] $links
     *
     * @return self
     */
    public function setLinks(array $links): self
    {
        $this->links = $links;
        return $this;
    }

    /**
     * @param string $name
     * @param Link|Reference $link
     *
     * @return self
     */
    public function addLink(string $name, $link): self
    {
        if ($link instanceof Link || $link instanceof Reference) {
            $this->links[$name] = $link;
        }
        return $this;
    }
}
