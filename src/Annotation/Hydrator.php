<?php
namespace Swagger\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Hydrator
{
    /**
     * @var string
     *
     * @Required
     **/
    public $name;
}
