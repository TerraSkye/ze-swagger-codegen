<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Laminas\Hydrator\HydratorInterface;

class LicenseHydrator implements HydratorInterface
{
    /**
     * @inheritDoc
     *
     * @param Schema\License $object
     *
     * @return Schema\License
     */
    public function hydrate(array $data, $object)
    {
        $object->setName($data['name']);

        if(isset($data['url'])) {
            $object->setUrl($data['url']);
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\License $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'name' => $object->getName(),
            'url'  => $object->getUrl()
        ];
    }
}
