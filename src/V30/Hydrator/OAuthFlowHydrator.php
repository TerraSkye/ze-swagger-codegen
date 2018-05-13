<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;

class OAuthFlowHydrator implements HydratorInterface
{
    /**
     * @inheritDoc
     *
     * @param Object\OAuthFlow $object
     *
     * @return Object\OAuthFlow
     */
    public function hydrate(array $data, $object)
    {
        $object->setAuthorizationUrl($data['authorizationUrl']);
        $object->setTokenUrl($data['tokenUrl']);

        if(isset($data['refreshUrl'])) {
            $object->setRefreshUrl($data['refreshUrl']);
        }

        if(isset($data['scopes'])) {
            $object->setScopes($data['scopes']);
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\OAuthFlow $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'authorizationUrl' => $object->getAuthorizationUrl(),
            'tokenUrl'  => $object->getTokenUrl(),
            'refreshUrl' => $object->getRefreshUrl(),
            'scopes' => $object->getScopes()
        ];
    }
}
