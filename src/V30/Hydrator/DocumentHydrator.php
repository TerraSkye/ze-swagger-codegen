<?php

namespace Swagger\V30\Hydrator;

use Swagger\V30\Schema;
use Laminas\Hydrator\HydratorInterface;
use Swagger\V30\Schema\Tag;
use Swagger\V30\Schema\Info;
use Swagger\V30\Schema\Server;
use Swagger\V30\Schema\PathItem;
use Swagger\V30\Schema\Components;
use Swagger\V30\Schema\SecurityRequirement;
use Swagger\V30\Schema\ExternalDocumentation;

class DocumentHydrator implements HydratorInterface
{
    /**
     * @var InfoHydrator
     */
    protected $infoHydrator;

    /**
     * @var ServerHydrator
     */
    protected $serverHydrator;

    /**
     * @var PathItemHydrator
     */
    protected $pathItemHydrator;

    /**
     * @var ComponentsHydrator
     */
    protected $componentsHydrator;

    /**
     * @var SecurityRequirementHydrator
     */
    protected $securityRequirementHydrator;

    /**
     * @var TagHydrator
     */
    protected $tagHydrator;

    /**
     * @var ExternalDocumentationHydrator
     */
    protected $externalDocsHydrator;

    /**
     * @param InfoHydrator     $infoHydrator
     * @param ServerHydrator   $serverHydrator
     * @param PathItemHydrator $pathItemHydrator
     * @param ComponentsHydrator $componentsHydrator
     * @param SecurityRequirementHydrator $securityRequirementHydrator
     * @param TagHydrator $tagHydrator
     * @param ExternalDocumentationHydrator $externalDocsHydrator
     */
    public function __construct(
        InfoHydrator $infoHydrator,
        ServerHydrator $serverHydrator,
        PathItemHydrator $pathItemHydrator,
        ComponentsHydrator $componentsHydrator,
        SecurityRequirementHydrator $securityRequirementHydrator,
        TagHydrator $tagHydrator,
        ExternalDocumentationHydrator $externalDocsHydrator
    ) {
        $this->infoHydrator = $infoHydrator;
        $this->serverHydrator = $serverHydrator;
        $this->pathItemHydrator = $pathItemHydrator;
        $this->componentsHydrator = $componentsHydrator;
        $this->securityRequirementHydrator = $securityRequirementHydrator;
        $this->tagHydrator = $tagHydrator;
        $this->externalDocsHydrator = $externalDocsHydrator;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\Document $object
     *
     * @return Schema\Document
     */
    public function hydrate(array $data, $object)
    {
        $object->setOpenapi($data['openapi']);

        $object->setInfo($this->infoHydrator->hydrate($data['info'], new Info()));

        if (isset($data['servers'])) {
            foreach ($data['servers'] as $server) {
                $object->addServer($this->serverHydrator->hydrate($server, new Server()));
            }
        }

        foreach ($data['paths'] as $path => $pathItem) {
            $object->addPath($path, $this->pathItemHydrator->hydrate($pathItem, new PathItem()));
        }

        if (isset($data['components'])) {
            $object->setComponents($this->componentsHydrator->hydrate($data['components'], new Components()));
        }

        if (isset($data['security'])) {
            foreach ($data['security'] as $security) {
                $object->addSecurity($this->securityRequirementHydrator->hydrate($data['security'], new SecurityRequirement()));
            }
        }

        if (isset($data['tag'])) {
            foreach ($data['tag'] as $tag) {
                $object->addTag($this->tagHydrator->hydrate($data['tag'], new Tag()));
            }
        }

        if (isset($data['externalDocs'])) {
            $object->setExternalDocs($this->externalDocsHydrator->hydrate($data['externalDocs'], new ExternalDocumentation()));
        }

        return $object;
    }

    /**
     * @inheritDoc
     *
     * @param Schema\Document $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'openapi' => $object->getOpenapi(),
            'info'    => $this->infoHydrator->extract($object->getInfo()),
        ];

        foreach ($object->getServers() as $server) {
            $data['servers'][] = $this->serverHydrator->extract($server);
        }

        foreach ($object->getPaths() as $path => $pathItem) {
            $data['paths'][$path] = $this->pathItemHydrator->extract($pathItem);
        }

        return $data;
    }
}
