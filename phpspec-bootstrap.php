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

use Laminas\Hydrator\HydratorInterface;

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

use Laminas\Validator\ValidatorInterface;

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

namespace Swagger\Command;

// use Swagger\Command\Codegen as RealCodegen;
// use Symfony\Component\Console\Input\InputInterface;
// use Symfony\Component\Console\Output\OutputInterface;
//
// class CodegenCommand extends RealCodegen
// {
//     /**
//      * @param  string $projectRoot
//      * @return self
//      */
//     public function setProjectRoot(string $projectRoot): self
//     {
//         $this->projectRoot = $projectRoot;
//
//         return $this;
//     }
//
//     public function getNamespacePathPublic(string $namespace, InputInterface $input, OutputInterface $output): string
//     {
//         return $this->getNamespacePath($namespace, $input, $output);
//     }
//
//     /**
//      * @param  string $composerPath
//      * @param  string $namespace
//      * @return bool
//      */
//     public function namespaceAutoloadExists(string $composerPath, string $namespace): bool
//     {
//         $composerJson = json_decode(file_get_contents($composerPath), true);
//
//         return isset($composerJson['autoload']['psr-4'][$namespace . '\\']);
//     }
// }

function realpath($path)
{
    return $path;
}

// namespace Swagger\Generator;
//
// use Swagger\Generator\DependenciesGenerator;
//
// class DependenciesGeneratorStub extends DependenciesGenerator
// {
//     /**
//      * @param  string $path
//      * @return bool
//      */
//     public function fileExists(string $path): bool
//     {
//         return file_exists($path);
//     }
//
//     /**
//      * @param  string $path
//      * @return bool
//      */
//     public function folderExists(string $path): bool
//     {
//         return is_dir($path);
//     }
//
//     /**
//      * @param  string $folder
//      * @return bool
//      */
//     public function assertFolderPermissions(string $folder): bool
//     {
//         return fileperms($folder) == 16877; //0755
//     }
// }
