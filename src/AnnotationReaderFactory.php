<?php

declare(strict_types=1);

namespace Swagger;

use Doctrine\Common\Annotations\FileCacheReader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Psr\Container\ContainerInterface;

class AnnotationReaderFactory
{
    /**
     * @param  ContainerInterface $container
     * @return Reader
     */
    public function __invoke(ContainerInterface $container) : Reader
    {
        $config = $container->get('config');

        if (!isset($config['debug'])) {
            throw new \RuntimeException('No debug configuration found.');
        }

        return new FileCacheReader(
            new AnnotationReader(),
            getcwd() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'swagger' . DIRECTORY_SEPARATOR . 'annotation' . DIRECTORY_SEPARATOR,
            $config['debug']
        );
    }
}
