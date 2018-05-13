<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;
use Swagger\V30\Object\OAuthFlow;

class OAuthFlowsHydrator implements HydratorInterface
{
    /**
     * @var OAuthFlowHydrator
     */
    protected $oAuthFlowHydrator;

    /**
     * Constructor
     * ---
     * @param OAuthFlowHydrator $oAuthFlowHydrator
     */
    public function __construct(OAuthFlowHydrator $oAuthFlowHydrator)
    {
        $this->oAuthFlowHydrator = $oAuthFlowHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Object\OAuthFlows $object
     *
     * @return Object\OAuthFlows
     */
    public function hydrate(array $data, $object)
    {
        $object->setImplicit($this->oAuthFlowHydrator->hydrate($data['implicit'], new OAuthFlow()));
        $object->setPassword($this->oAuthFlowHydrator->hydrate($data['password'], new OAuthFlow()));
        $object->setClientCredentials($this->oAuthFlowHydrator->hydrate($data['clientCredentials'], new OAuthFlow()));
        $object->setAuthorizationCode($this->oAuthFlowHydrator->hydrate($data['authorizationCode'], new OAuthFlow()));

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\OAuthFlows $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'implicit' => $this->oAuthFlowHydrator->extract($object->getImplicit()),
            'password' => $this->oAuthFlowHydrator->extract($object->getPassword()),
            'clientCredentials' => $this->oAuthFlowHydrator->extract($object->getClientCredentials()),
            'authorizationCode' => $this->oAuthFlowHydrator->extract($object->getAuthorizationCode()),
        ];
    }
}
