<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;
use Swagger\V30\Object\PathItem;

class CallbackHydrator implements HydratorInterface
{
    /**
     * @var PathItemHydrator
     */
    protected $pathItemHydrator;

    /**
     * Constructor
     * ---
     * @param PathItemHydrator $pathItemHydrator
     */
    public function __construct(PathItemHydrator $pathItemHydrator)
    {
        $this->pathItemHydrator = $pathItemHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Callback $object
     *
     * @return Object\Callback
     */
    public function hydrate(array $data, $object)
    {
        foreach ($data as $name => $pathItem) {
            $object->setExpression($name, $this->pathItemHydrator->hydrate($pathItem, new PathItem()));
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Callback $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [];

        foreach ($object->getExpressions() as $name => $pathItem) {
            $data[$name] = $this->pathItemHydrator->extract($pathItem);
        }

        return $data;
    }
}
