<?php

declare(strict_types=1);

namespace Swagger;

use Doctrine\Common\Annotations\FileCacheReader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Psr\Container\ContainerInterface;

class AnnotationReaderFactory
{
    public function __invoke(ContainerInterface $container) : Reader
    {
        return new FileCacheReader(
            new AnnotationReader(),
            getcwd() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'swagger' . DIRECTORY_SEPARATOR . 'annotation' . DIRECTORY_SEPARATOR,
            $debug = true
        );
    }
}
