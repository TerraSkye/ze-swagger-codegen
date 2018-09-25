<?php
namespace Swagger\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Validators
{
    /**
     * @var array<\Swagger\Annotation\Validator>
     *
     * @Required
     **/
    public $validators = [];
}
