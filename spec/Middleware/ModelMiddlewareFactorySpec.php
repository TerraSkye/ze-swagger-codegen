<?php

namespace spec\Swagger\Middleware;

use App\Hydrator\TestHydrator;

use Psr\Container\ContainerInterface;
use Zend\Hydrator\HydratorPluginManager;
use Swagger\Middleware\ModelMiddleware;
use Swagger\Middleware\ModelMiddlewareFactory;
use PhpSpec\ObjectBehavior;
use Doctrine\Common\Annotations\Reader;
use Swagger\Annotation;
use Prophecy\Argument;
use Zend\Validator\ValidatorChain;
use Zend\Validator\ValidatorPluginManager;
use App\Validator\TestValidator;

class ModelMiddlewareFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ModelMiddlewareFactory::class);
    }

    public function it_is_callable_with_a_single_validator(
        ContainerInterface $container,
        HydratorPluginManager $hydratorPluginManager,
        Reader $annotationReader,
        Annotation\Hydrator $hydratorAnnotation,
        ValidatorChain $validatorChain,
        Annotation\Validators $validatorsAnnotation,
        Annotation\Validator $validatorAnnotation,
        TestValidator $testValidator,
        TestHydrator $testHydrator
    ) {
        $container->get('HydratorManager')->willReturn($hydratorPluginManager);
        $container->get(ValidatorChain::class)->willReturn($validatorChain);
        $container->get(\Swagger\AnnotationReader::class)->willReturn($annotationReader);

        $annotationReader->getClassAnnotation(Argument::type(\ReflectionClass::class), Annotation\Hydrator::class)->willReturn($hydratorAnnotation);
        $hydratorAnnotation->name = TestHydrator::class;
        $hydratorPluginManager->get(TestHydrator::class)->willReturn($testHydrator);

        $annotationReader->getPropertyAnnotation(Argument::type(\ReflectionProperty::class), Annotation\Validators::class)->willReturn(null);
        $annotationReader->getPropertyAnnotation(Argument::type(\ReflectionProperty::class), Annotation\Validators::class)->shouldBeCalled();

        $annotationReader->getPropertyAnnotation(Argument::type(\ReflectionProperty::class), Annotation\Validator::class)->willReturn($validatorAnnotation);
        $annotationReader->getPropertyAnnotation(Argument::type(\ReflectionProperty::class), Annotation\Validator::class)->shouldBeCalled();

        $validatorAnnotation->name = TestValidator::class;

        $validatorChain->addByName(TestValidator::class)->shouldBeCalled();

        $this->__invoke($container, \App\Model\Test::class)->shouldBeAnInstanceOf(ModelMiddleware::class);
    }

    public function it_isnt_callable_when_hydrator_is_invalid(
        ContainerInterface $container,
        HydratorPluginManager $hydratorPluginManager,
        Reader $annotationReader
    ) {
        $container->get('HydratorManager')->willReturn($hydratorPluginManager);
        $container->get(\Swagger\AnnotationReader::class)->willReturn($annotationReader);

        $annotationReader->getClassAnnotation(Argument::type(\ReflectionClass::class), Annotation\Hydrator::class)->willReturn(null);

        $container->get(ValidatorChain::class)->shouldNotBeCalled();

        $this->shouldThrow('\Exception')->during('__invoke', [$container, \App\Model\Test::class]);
    }

    public function it_is_callable_with_multiple_validators(
        ContainerInterface $container,
        HydratorPluginManager $hydratorPluginManager,
        Reader $annotationReader,
        Annotation\Hydrator $hydratorAnnotation,
        ValidatorChain $validatorChain,
        Annotation\Validators $validatorsAnnotation,
        Annotation\Validator $validatorAnnotation,
        TestValidator $testValidator,
        TestHydrator $testHydrator
    ) {
        $container->get('HydratorManager')->willReturn($hydratorPluginManager);
        $container->get(ValidatorChain::class)->willReturn($validatorChain);
        $container->get(\Swagger\AnnotationReader::class)->willReturn($annotationReader);

        $annotationReader->getClassAnnotation(Argument::type(\ReflectionClass::class), Annotation\Hydrator::class)->willReturn($hydratorAnnotation);
        $hydratorAnnotation->name = TestHydrator::class;
        $hydratorPluginManager->get(TestHydrator::class)->willReturn($testHydrator);

        $annotationReader->getPropertyAnnotation(Argument::type(\ReflectionProperty::class), Annotation\Validators::class)->willReturn($validatorsAnnotation);
        $validatorAnnotation->name = TestValidator::class;
        $validatorsAnnotation->validators = [
            $validatorAnnotation
        ];

        $validatorChain->addByName(TestValidator::class)->shouldBeCalled();

        $annotationReader->getPropertyAnnotation(Argument::type(\ReflectionProperty::class), Annotation\Validator::class)->willReturn(null);

        $this->__invoke($container, \App\Model\Test::class)->shouldBeAnInstanceOf(ModelMiddleware::class);
    }
}
