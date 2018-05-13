<?php

namespace Swagger\V30\Hydrator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory as ZendReflectionFactory;

class ReflectionBasedAbstractFactory extends ZendReflectionFactory
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return parent::__invoke($container->get('HydratorManager'), $requestedName, $options);
    }
}
