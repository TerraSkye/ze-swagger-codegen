<?php

namespace spec\Swagger;

use Swagger\AnnotationReaderFactory;
use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Annotations\Reader;

class AnnotationReaderFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(AnnotationReaderFactory::class);
    }

    public function it_is_callable(ContainerInterface $container)
    {
        $this->__invoke($container)->shouldBeAnInstanceOf(Reader::class);
    }
}
