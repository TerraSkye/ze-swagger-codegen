<?php
namespace Swagger;

use Swagger\Exception\CodegenException;

class Composer
{
    /**
     * @param string $projectRoot
     * @return array Associative array of namespace/path pairs
     * @throws CodegenException
     */
    public function getComposerAutoloaders(string $projectRoot) : array
    {
        //Check PSR-4 autoloading from Composer autoload
        $autoloadFile = $projectRoot . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'composer' . DIRECTORY_SEPARATOR . '/autoload_psr4.php';

        if (is_file($autoloadFile)) {
            $mapping = array_map(function ($value) {
                return array_shift($value);
            }, require $autoloadFile);

            return $mapping;
        }

        //Fallback to project composer.json when autoloadfile is not present (yet)
        $composerPath = $this->getComposerJsonPath($projectRoot);
        if (!file_exists($composerPath)) {
            throw CodegenException::missingComposerJson();
        }

        $composer = json_decode(file_get_contents($composerPath), true);

        if (json_last_error() !== \JSON_ERROR_NONE) {
            throw CodegenException::invalidComposerJson(json_last_error_msg());
        }

        if (! isset($composer['autoload']['psr-4'])) {
            throw CodegenException::missingComposerAutoloaders();
        }

        if (! is_array($composer['autoload']['psr-4'])) {
            throw CodegenException::missingComposerAutoloaders();
        }

        return $composer['autoload']['psr-4'];
    }

    /**
     * @param string $projectRoot
     * @return string
     */
    public function getComposerJsonPath(string $projectRoot): string
    {
        return sprintf('%s/composer.json', $projectRoot);
    }
}
