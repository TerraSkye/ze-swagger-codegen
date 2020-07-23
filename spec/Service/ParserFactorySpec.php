<?php

namespace spec\Swagger\Service;

use Psr\Container\ContainerInterface;
use Swagger\Parser;
use Swagger\Service\ParserFactory;
use PhpSpec\ObjectBehavior;
use Swagger\V30\Hydrator\DocumentHydrator;
use Laminas\Hydrator\HydratorPluginManager;

class ParserFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ParserFactory::class);
    }

    public function it_is_callable(
        ContainerInterface $container,
        HydratorPluginManager $hydratorPluginManager,
        DocumentHydrator $documentHydrator
    ) {
        $container->get('HydratorManager')->willReturn($hydratorPluginManager);
        $hydratorPluginManager->get(DocumentHydrator::class)->willReturn($documentHydrator);

        $this->__invoke($container)->shouldBeAnInstanceOf(Parser::class);
    }
}
