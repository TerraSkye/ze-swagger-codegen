<?php

namespace spec\Swagger\Service;

use Psr\Container\ContainerInterface;
use Swagger\Service\ValidatorChainFactory;
use PhpSpec\ObjectBehavior;
use Zend\Validator\ValidatorChain;
use Zend\Validator\ValidatorPluginManager;

class ValidatorChainFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ValidatorChainFactory::class);
    }

    public function it_can_create_the_service(
        ContainerInterface $container,
        ValidatorPluginManager $validatorPluginManager
    ) {
        $container->get('ValidatorManager')->willReturn($validatorPluginManager);
        $container->get('ValidatorManager')->shouldBeCalled();

        $this->__invoke($container)->shouldBeAnInstanceOf(ValidatorChain::class);
    }
}
