<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;
use Swagger\V30\Object\OAuthFlows;

class SecuritySchemeHydrator implements HydratorInterface
{
    /**
     * @var OAuthFlowsHydrator
     */
    protected $oAuthFlowsHydrator;

    /**
     * Constructor
     * ---
     * @param OAuthFlowsHydrator $oAuthFlowsHydrator
     */
    public function __construct(OAuthFlowsHydrator $oAuthFlowsHydrator)
    {
        $this->oAuthFlowsHydrator = $oAuthFlowsHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Object\SecurityScheme $object
     *
     * @return Object\SecurityScheme
     */
    public function hydrate(array $data, $object)
    {
        $object->setType($data['type']);

        if (isset($data['description'])) {
            $object->setDescription($data['description']);
        }

        $object->setName($data['name']);
        $object->setIn($data['in']);
        $object->setScheme($data['scheme']);

        if (isset($data['bearerFormat'])) {
            $object->setBearerFormat($data['bearerFormat']);
        }

        $object->setFlows($this->oAuthFlowsHydrator->hydrate($data['flows'], new OAuthFlows()));

        $object->setOpenIdConnectUrl($data['openIdConnectUrl']);

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\SecurityScheme $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'type' => $object->getType(),
            'description' => $object->getDescription(),
            'name' => $object->getName(),
            'in' => $object->getIn(),
            'scheme' => $object->getScheme(),
            'bearerFormat' => $object->getBearerFormat(),
            'flows' => $this->oAuthFlowsHydrator->extract($object->getFlows()),
            'openIdConnectUrl' => $object->getOpenIdConnectUrl(),
        ];
    }
}
