<?php

namespace spec\Swagger\Command\Service;

use Swagger\Template;
use Psr\Container\ContainerInterface;
use Zend\Hydrator\HydratorPluginManager;
use Swagger\Generator;
use Swagger\V30\Hydrator\DocumentHydrator;
use Swagger\Command\Service\CodegenFactory;
use PhpSpec\ObjectBehavior;
use Swagger\Command\Codegen;
use Swagger\Ignore;

class CodegenFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CodegenFactory::class);
    }

    public function it_is_callable(
        ContainerInterface $container,
        HydratorPluginManager $hydratorPluginManager,
        Template $template,
        DocumentHydrator $documentHydrator,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\ModelGenerator $modelGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Generator\HydratorGenerator $hydratorGenerator,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\ApiGenerator $apiGenerator,
        Ignore $ignoreService
    ) {
        $container->get('HydratorManager')->willReturn($hydratorPluginManager);
        $hydratorPluginManager->get(DocumentHydrator::class)->willReturn($documentHydrator);

        $container->get(Template::class)->willReturn($template);

        $container->get(Generator\HandlerGenerator::class)->willReturn($handlerGenerator);
        $container->get(Generator\ModelGenerator::class)->willReturn($modelGenerator);
        $container->get(Generator\RoutesGenerator::class)->willReturn($routesGenerator);
        $container->get(Generator\HydratorGenerator::class)->willReturn($hydratorGenerator);
        $container->get(Generator\DependenciesGenerator::class)->willReturn($dependenciesGenerator);
        $container->get(Generator\ApiGenerator::class)->willReturn($apiGenerator);
        $container->get(Ignore::class)->willReturn($ignoreService);

        $this->__invoke($container)->shouldBeAnInstanceOf(Codegen::class);
    }
}
