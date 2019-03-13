<?php

namespace spec\Swagger\Command\Service;

use Swagger\Parser;
use Swagger\Composer;
use Swagger\Template;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputDefinition;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Swagger\Generator;
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
        Template $template,
        Generator\HandlerGenerator $handlerGenerator,
        Generator\ModelGenerator $modelGenerator,
        Generator\RoutesGenerator $routesGenerator,
        Generator\HydratorGenerator $hydratorGenerator,
        Generator\DependenciesGenerator $dependenciesGenerator,
        Generator\ApiGenerator $apiGenerator,
        Ignore $ignoreService,
        EventDispatcherInterface $eventDispatcher,
        Parser $parser,
        Composer $composer,
        InputDefinition $definition
    ) {
        $container->get(Template::class)->willReturn($template);

        $container->get(Generator\HandlerGenerator::class)->willReturn($handlerGenerator);
        $container->get(Generator\ModelGenerator::class)->willReturn($modelGenerator);
        $container->get(Generator\RoutesGenerator::class)->willReturn($routesGenerator);
        $container->get(Generator\HydratorGenerator::class)->willReturn($hydratorGenerator);
        $container->get(Generator\DependenciesGenerator::class)->willReturn($dependenciesGenerator);
        $container->get(Generator\ApiGenerator::class)->willReturn($apiGenerator);
        $container->get(Ignore::class)->willReturn($ignoreService);
        $container->get('event_dispatcher')->willReturn($eventDispatcher);
        $container->get(Parser::class)->willReturn($parser);
        $container->get(Composer::class)->willReturn($composer);
        $container->get(InputDefinition::class)->willReturn($definition);

        $this->__invoke($container)->shouldBeAnInstanceOf(Codegen::class);
    }
}
