<?php

declare(strict_types=1);

namespace Swagger\Service;

use Psr\Container\ContainerInterface;
use Swagger\Template;

class TemplateFactory
{
    /**
     * @param  ContainerInterface $container
     *
     * @return Template
     */
    public function __invoke(ContainerInterface $container) : Template
    {
        return new Template($container->get('Cache.Swagger.Template'));
    }
}
