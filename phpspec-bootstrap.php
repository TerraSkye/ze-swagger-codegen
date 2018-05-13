<?php

namespace App\Model;

class Test
{
    /**
     * @var string
     */
    protected $property;

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @param string $property
     *
     * @return self
     */
    public function setProperty(string $property): self
    {
        $this->property = $property;
        return $this;
    }
}

namespace App\Hydrator;

use Zend\Hydrator\HydratorInterface;

class TestHydrator implements HydratorInterface
{
    public function hydrate(array $data, $object)
    {
        return $object;
    }

    public function extract($object)
    {
        return [];
    }
}

namespace App\Validator;

use Zend\Validator\ValidatorInterface;

class TestValidator implements ValidatorInterface
{
    public function isValid($value)
    {
        return true;
    }

    public function getMessages()
    {
        return [];
    }
}
