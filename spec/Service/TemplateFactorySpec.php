<?php

namespace spec\Swagger\Service;

use Psr\Container\ContainerInterface;
use Swagger\Service\TemplateFactory;
use PhpSpec\ObjectBehavior;
use Zend\Cache\Storage\StorageInterface;
use Swagger\Template;

class TemplateFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TemplateFactory::class);
    }

    public function it_is_callable(ContainerInterface $container, StorageInterface $storage)
    {
        $container->get('Cache.Swagger.Template')->willReturn($storage);
        $container->get('Cache.Swagger.Template')->shouldBeCalled();

        $this->__invoke($container)->shouldBeAnInstanceOf(Template::class);
    }
}
