<?php

declare(strict_types=1);

namespace Swagger\Exception;

use RuntimeException;

class CodegenException extends RuntimeException
{
    public static function missingSwaggerJson() : self
    {
        return new self('Could not find a swagger.json in the project root');
    }

    public static function versionDetectFailure() : self
    {
        return new self('Could not detect OpenAPI version.');
    }

    public static function missingComposerJson() : self
    {
        return new self('Could not find a composer.json in the project root');
    }

    /**
     * @param string $error Error string related to JSON_ERROR_* constant
     */
    public static function invalidComposerJson(string $error) : self
    {
        return new self(sprintf(
            'Unable to parse composer.json: %s',
            $error
        ));
    }

    public static function missingComposerAutoloaders() : self
    {
        return new self('composer.json does not define any PSR-4 autoloaders');
    }

    public static function autoloaderNotFound(string $class) : self
    {
        return new self(sprintf(
            'Unable to match %s to an autoloadable PSR-4 namespace',
            $class
        ));
    }

    public static function missingYamlExtension() : self
    {
        return new self('A openapi.yml has been found but the Yaml extension is not loaded.');
    }

    public static function unknownFileExtension(string $ext) : self
    {
        return new self(sprintf('The given file extension "%s" is not supported.', $ext));
    }
}
