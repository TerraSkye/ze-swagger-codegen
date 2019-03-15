<?php

declare(strict_types=1);

namespace Swagger\Middleware;

use Psr\Container\ContainerInterface;
use Swagger\Annotation\Hydrator;
use Swagger\Annotation\Validator;
use Swagger\Annotation\Validators;
use Zend\Validator\ValidatorChain;

class ModelMiddlewareFactory
{
    /**
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     *
     * @return ModelMiddleware
     */
    public function __invoke(ContainerInterface $container, string $requestedName) : ModelMiddleware
    {
        $hydratorManager = $container->get('HydratorManager');
        $annotationReader = $container->get('Swagger\AnnotationReader');

        $reflClass = new \ReflectionClass($requestedName);

        $hydrator = $annotationReader->getClassAnnotation($reflClass, Hydrator::class);

        if (!$hydrator instanceof Hydrator) {
            throw new \Exception('No Hydrator configured for Model: ' . $requestedName . '. Please add the Hydrator annotation to the Model.');
        }

        $validatorChains = [];
        foreach ($reflClass->getProperties() as $property) {
            $validatorChain = $container->get(ValidatorChain::class);

            $validators = $annotationReader->getPropertyAnnotation($property, Validators::class);

            if ($validators instanceof Validators) {
                foreach ($validators->validators as $validator) {
                    $validatorChain->addByName($validator->name, $validator->config);
                }
            }

            $validator = $annotationReader->getPropertyAnnotation($property, Validator::class);

            if ($validator instanceof Validator) {
                $validatorChain->addByName($validator->name, $validator->config);
            }

            $validatorChains[$property->name] = $validatorChain;
        }

        return new ModelMiddleware(new $requestedName(), $hydratorManager->get($hydrator->name), $validatorChains);
    }
}
