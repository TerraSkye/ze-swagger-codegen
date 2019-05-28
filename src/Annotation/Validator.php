<?php
namespace Swagger\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class Validator
{
    /**
     * @var string
     *
     * @Required
     **/
    public $name;

    /**
     * @var array
     */
    public $options = [];
}
