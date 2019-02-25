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

    /**
     * @param  ContainerInterface $container
     * @return bool
     */
    public function it_is_callable(ContainerInterface $container)
    {
        //No debug config
        $container->get('config')->willReturn([]);
        $this->shouldThrow(\RuntimeException::class)->during('__invoke', [$container]);

        //With debug config
        $container->get('config')->willReturn([
            'debug' => true
        ]);
        $this->__invoke($container)->shouldBeAnInstanceOf(Reader::class);

        $container->get('config')->willReturn([
            'debug' => false
        ]);
        $this->__invoke($container)->shouldBeAnInstanceOf(Reader::class);

        $this->shouldNotThrow(\RuntimeException::class)->during('__invoke', [$container]);
    }
}
