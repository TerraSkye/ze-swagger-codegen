<?php

declare(strict_types=1);

namespace Swagger\Command\Service;

use Swagger\Ignore;
use Swagger\Template;
use Psr\Container\ContainerInterface;
use Swagger\Command\Codegen;
use Swagger\Generator\HandlerGenerator;
use Swagger\Generator\ModelGenerator;
use Swagger\Generator\RoutesGenerator;
use Swagger\Generator\HydratorGenerator;
use Swagger\Generator\DependenciesGenerator;
use Swagger\Generator\ApiGenerator;
use Swagger\V30\Hydrator\DocumentHydrator;

class CodegenFactory
{
    /**
     * @param  ContainerInterface $container
     *
     * @return Codegen
     */
    public function __invoke(ContainerInterface $container) : Codegen
    {
        $hydratorManager = $container->get('HydratorManager');

        $container->get(Ignore::class);

        return new Codegen(
            $hydratorManager->get(DocumentHydrator::class),
            $container->get(Template::class),
            $container->get(HandlerGenerator::class),
            $container->get(ModelGenerator::class),
            $container->get(RoutesGenerator::class),
            $container->get(HydratorGenerator::class),
            $container->get(DependenciesGenerator::class),
            $container->get(ApiGenerator::class)
        );
    }
}
