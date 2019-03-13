<?php

declare(strict_types=1);

namespace Swagger\Command\Service;

use Swagger\Parser;
use Swagger\Composer;
use Swagger\Template;
use Psr\Container\ContainerInterface;
use Swagger\Command\Codegen;
use Swagger\Generator\HandlerGenerator;
use Swagger\Generator\ModelGenerator;
use Swagger\Generator\RoutesGenerator;
use Swagger\Generator\HydratorGenerator;
use Swagger\Generator\DependenciesGenerator;
use Swagger\Generator\ApiGenerator;
use Symfony\Component\Console\Input\InputDefinition;

class CodegenFactory
{
    /**
     * @param  ContainerInterface $container
     *
     * @return Codegen
     */
    public function __invoke(ContainerInterface $container) : Codegen
    {
        return new Codegen(
            $container->get(Parser::class),
            $container->get(Template::class),
            $container->get(HandlerGenerator::class),
            $container->get(ModelGenerator::class),
            $container->get(RoutesGenerator::class),
            $container->get(HydratorGenerator::class),
            $container->get(DependenciesGenerator::class),
            $container->get(ApiGenerator::class),
            $container->get('event_dispatcher'),
            $container->get(Composer::class),
            $container->get(InputDefinition::class)
        );
    }
}
