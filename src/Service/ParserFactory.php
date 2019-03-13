<?php

namespace Swagger\Service;

use Psr\Container\ContainerInterface;
use Swagger\Parser;
use Swagger\V30\Hydrator\DocumentHydrator;

class ParserFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return Parser
     */
    public function __invoke(ContainerInterface $container): Parser
    {
        $hydratorManager = $container->get('HydratorManager');

        return new Parser($hydratorManager->get(DocumentHydrator::class));
    }
}
