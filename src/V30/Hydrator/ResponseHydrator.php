<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Zend\Hydrator\HydratorInterface;

use Swagger\V30\Schema\Link;
use Swagger\V30\Schema\Header;
use Swagger\V30\Schema\MediaType;
use Swagger\V30\Schema\Reference;

class ResponseHydrator implements HydratorInterface
{
    /**
     * @var HeaderHydrator
     */
    protected $headerHydrator;

    /**
     * @var MediaTypeHydrator
     */
    protected $mediaTypeHydrator;

    /**
     * @var LinkHydrator
     */
    protected $linkHydrator;

    /**
     * @var ReferenceHydrator
     */
    protected $referenceHydrator;

    /**
     * Constructor
     * ---
     * @param HeaderHydrator    $headerHydrator
     * @param MediaTypeHydrator $mediaTypeHydrator
     * @param LinkHydrator      $linkHydrator
     * @param ReferenceHydrator $referenceHydrator
     */
    public function __construct(
        HeaderHydrator $headerHydrator,
        MediaTypeHydrator $mediaTypeHydrator,
        LinkHydrator $linkHydrator,
        ReferenceHydrator $referenceHydrator
    ) {
        $this->headerHydrator = $headerHydrator;
        $this->mediaTypeHydrator = $mediaTypeHydrator;
        $this->linkHydrator = $linkHydrator;
        $this->referenceHydrator = $referenceHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\Response $object
     *
     * @return Schema\Response
     */
    public function hydrate(array $data, $object)
    {
        $object->setDescription($data['description']);

        if(isset($data['headers'])) {
            foreach ($data['headers'] as $name => $header) {
                $object->addHeader($name, isset($header['$ref'])? $this->referenceHydrator->hydrate($header, new Reference()) : $this->headerHydrator->hydrate($header, new Header()));
            }
        }

        if(isset($data['content'])) {
            foreach ($data['content'] as $name => $content) {
                $object->addContent($name, isset($content['$ref'])? $this->referenceHydrator->hydrate($content, new Reference()) :$this->mediaTypeHydrator->hydrate($content, new MediaType()));
            }
        }

        if(isset($data['links'])) {
            foreach ($data['links'] as $name => $link) {
                $object->addLink($name, isset($link['$ref'])? $this->referenceHydrator->hydrate($link, new Reference()) : $this->linkHydrator->hydrate($link, new Link()));
            }
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\Response $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'description' => $object->getDescription()
        ];

        foreach ($object->getHeaders() as $name => $header) {
            $data['headers'][$name] = $header instanceof Reference? $this->referenceHydrator->extract($header): $this->headerHydrator->extract($header);
        }

        foreach ($object->getContent() as $name => $content) {
            $data['content'][$name] = $content instanceof Reference? $this->referenceHydrator->extract($content): $this->mediaTypeHydrator->extract($content);
        }

        foreach ($object->getLinks() as $name => $link) {
            $data['links'][$name] = $link instanceof Reference? $this->referenceHydrator->extract($link): $this->linkHydrator->extract($link);
        }

        return $data;
    }
}
