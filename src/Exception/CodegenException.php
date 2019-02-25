<?php

declare(strict_types=1);

namespace Swagger\Exception;

use RuntimeException;

class CodegenException extends RuntimeException
{
    /**
     * @return self
     */
    public static function missingSwaggerJson() : self
    {
        return new self('Could not find a openapi.json/openapi.yml in the project root');
    }

    /**
     * @return self
     */
    public static function versionDetectFailure() : self
    {
        return new self('Could not detect OpenAPI version.');
    }

    /**
     * @return self
     */
    public static function missingComposerJson() : self
    {
        return new self('Could not find a composer.json in the project root');
    }

    /**
     * @param string $error Error string related to JSON_ERROR_* constant
     * @return self
     */
    public static function invalidComposerJson(string $error) : self
    {
        return new self(sprintf(
            'Unable to parse composer.json: %s',
            $error
        ));
    }

    /**
     * @return self
     */
    public static function missingComposerAutoloaders() : self
    {
        return new self('composer.json does not define any PSR-4 autoloaders');
    }

    /**
     * @param string $class
     * @return self
     */
    public static function autoloaderNotFound(string $class) : self
    {
        return new self(sprintf(
            'Unable to match %s to an autoloadable PSR-4 namespace',
            $class
        ));
    }

    /**
     * @return self
     */
    public static function missingYamlExtension() : self
    {
        return new self('A openapi.yml has been found but the Yaml extension is not loaded.');
    }

    /**
     * @param string $ext
     * @return self
     */
    public static function unknownFileExtension(string $ext) : self
    {
        return new self(sprintf('The given file extension "%s" is not supported.', $ext));
    }
}
