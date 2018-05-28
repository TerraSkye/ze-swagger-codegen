<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Zend\Hydrator\HydratorInterface;
use Swagger\V30\Schema\OAuthFlows;

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
     * @param Schema\SecurityScheme $object
     *
     * @return Schema\SecurityScheme
     */
    public function hydrate(array $data, $object)
    {
        $object->setType($data['type']);

        if (isset($data['description'])) {
            $object->setDescription($data['description']);
        }

        if (isset($data['name'])) {
            $object->setName($data['name']);
        }

        if (isset($data['in'])) {
            $object->setIn($data['in']);
        }

        $object->setScheme($data['scheme']);

        if (isset($data['bearerFormat'])) {
            $object->setBearerFormat($data['bearerFormat']);
        }

        if (isset($data['flows'])) {
            $object->setFlows($this->oAuthFlowsHydrator->hydrate($data['flows'], new OAuthFlows()));
        }

        if (isset($data['openIdConnectUrl'])) {
            $object->setOpenIdConnectUrl($data['openIdConnectUrl']);
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\SecurityScheme $object
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
