<?php

namespace Swagger\Service;

use Psr\Container\ContainerInterface;
use Laminas\Validator\ValidatorChain;

class ValidatorChainFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ValidatorChain
     */
    public function __invoke(ContainerInterface $container): ValidatorChain
    {
        $validatorChain = new ValidatorChain();

        $validatorChain->setPluginManager($container->get('ValidatorManager'));

        return $validatorChain;
    }
}
