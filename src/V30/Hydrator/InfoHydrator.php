<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Object;
use Zend\Hydrator\HydratorInterface;
use Swagger\V30\Object\Contact;
use Swagger\V30\Object\License;

class InfoHydrator implements HydratorInterface
{
    /**
     * @var ContactHydrator
     */
    protected $contactHydrator;

    /**
     * @var LicenseHydrator
     */
    protected $licenseHydrator;

    /**
     * @param ContactHydrator $contactHydrator
     * @param LicenseHydrator $licenseHydrator
     */
    public function __construct(ContactHydrator $contactHydrator, LicenseHydrator $licenseHydrator)
    {
        $this->contactHydrator = $contactHydrator;
        $this->licenseHydrator = $licenseHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Info $object
     *
     * @return Object\Info
     */
    public function hydrate(array $data, $object)
    {
        $object->setTitle($data['title']);

        if (isset($data['description'])) {
            $object->setDescription($data['description']);
        }

        if (isset($data['termsOfService'])) {
            $object->setTermsOfService($data['termsOfService']);
        }

        if (isset($data['contact'])) {
            $object->setContact($this->contactHydrator->hydrate($data['contact'], new Contact()));
        }
        if (isset($data['license'])) {
            $object->setLicense($this->licenseHydrator->hydrate($data['license'], new License()));
        }

        $object->setVersion($data['version']);

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Object\Info $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'title' => $object->getTitle(),
            'description' => $object->getDescription(),
            'termsOfService' => $object->getTermsOfService(),
            'contact' => $object->getContact()? $this->contactHydrator->extract($object->getContact()) : null,
            'license' => $object->getLicense()? $this->licenseHydrator->extract($object->getLicense()):null,
            'version' => $object->getVersion(),
        ];
    }
}
