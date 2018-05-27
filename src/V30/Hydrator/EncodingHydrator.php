<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;
use Swagger\V30\Object\Header;
use Swagger\V30\Object\Reference;

class EncodingHydrator implements HydratorInterface
{
    /**
     * @var ReferenceHydrator
     */
    protected $referenceHydrator;

    /**
     * @var HeaderHydrator
     */
    protected $headerHydrator;

    /**
     * @param ReferenceHydrator $referenceHydrator
     * @param HeaderHydrator    $headerHydrator
     */
    public function __construct(
        ReferenceHydrator $referenceHydrator,
        HeaderHydrator $headerHydrator
    ) {
        $this->referenceHydrator = $referenceHydrator;
        $this->headerHydrator = $headerHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Encoding $object
     *
     * @return Object\Encoding
     */
    public function hydrate(array $data, $object)
    {
        $object->setContentType($data['contentType']);

        if (isset($data['headers'])) {
            foreach ($data['headers'] as $header) {
                $object->addHeader(isset($header['$ref']) ? $this->referenceHydrator->hydrate($header, new Reference()) : $this->headerHydrator->hydrate($header, new Header()));
            }
        }

        $object->setStyle($data['style']);
        $object->setExplode($data['explode']);
        $object->setAllowReserved($data['allowReserved']);

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Encoding $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'contentType' => $object->getContentType(),
            'style'  => $object->getStyle(),
            'explode' => $object->getExplode(),
            'allowedReserverd' => $object->getAllowReserved()
        ];

        foreach ($object->getHeaders() as $header) {
            $data['headers'][] = $header instanceof Reference? $this->referenceHydrator->extract($header): $this->headerHydrator->extract($header);
        }

        return $data;
    }
}
