<?php

namespace Swagger;

use Swagger\Exception\CodegenException;
use Swagger\V30\Schema\Document as V30Document;
use Swagger\V30\Hydrator\DocumentHydrator;

class Parser
{
    /**
     * @var DocumentHydrator
     */
    protected $documentHydrator;

    /**
     * @param DocumentHydrator $documentHydrator
     */
    public function __construct(DocumentHydrator $documentHydrator)
    {
        $this->documentHydrator = $documentHydrator;
    }

    /**
     * @param  string $file
     *
     * @return V30Document
     */
    public function parseFile(string $file): V30Document
    {
        $fileContents = file_get_contents($file);

        $ext = pathinfo($file, PATHINFO_EXTENSION);

        switch ($ext) {
            case 'json':
                $data = json_decode($fileContents, true);
                break;
            case 'yml':
                $data = yaml_parse($fileContents);
                break;
            default:
                throw CodegenException::unknownFileExtension($ext);
                break;
        }

        return $this->parse($data);
    }

    /**
     * @param  array $data
     *
     * @return V30Document
     */
    public function parse(array $data): V30Document
    {
        if (!empty($data)) {
            $version = $this->detectOpenAPIVersion($data);

            switch ($version) {
                case strpos($version, '3.0') === 0:
                    return $this->documentHydrator->hydrate($data, new V30Document());
            }
        }
    }

    /**
     * @param  array  $rawData
     *
     * @return string
     *
     * @throws CodegenException
     */
    public function detectOpenAPIVersion(array $rawData): string
    {
        if (array_key_exists('swagger', $rawData)) {
            return $rawData['swagger'];
        }

        if (array_key_exists('openapi', $rawData)) {
            return $rawData['openapi'];
        }

        throw CodegenException::versionDetectFailure();
    }
}
