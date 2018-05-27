<?php
declare(strict_types=1);

namespace Swagger\V30\Object;

class MediaType
{
    /**
     * @var Schema|Reference
     */
    protected $schema;

    /**
     * @var mixed
     */
    protected $example;

    /**
     * @var Example[]|Reference[]
     */
    protected $examples = [];

    /**
     * @var Encoding[]
     */
    protected $encoding = [];

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
     * @return Example[]|Reference[]
     */
    public function getExamples(): array
    {
        return $this->examples;
    }

    /**
     * @param Example[]|Reference[] $examples
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
     * @return Encoding[]
     */
    public function getEncoding(): array
    {
        return $this->encoding;
    }

    /**
     * @param Encoding[] $encoding
     *
     * @return self
     */
    public function setEncoding(array $encoding): self
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * @param Encoding $encoding
     *
     * @return self
     */
    public function addEncoding(Encoding $encoding): self
    {
        array_push($this->encoding, $encoding);
        return $this;
    }
}
