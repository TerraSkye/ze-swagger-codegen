<?php

declare(strict_types=1);

namespace Swagger\Service;

use FilesystemIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Swagger\Ignore;

class IgnoreFactory
{
    /**
     * @return Ignore
     */
    public function __invoke(): Ignore
    {
        return new Ignore(
            $this->findIgnoreFiles()
        );
    }

    /**
     * @return array
     */
    protected function findIgnoreFiles(): array
    {
        return preg_grep('~\.swagger-codegen-ignore\b~i', array_values($this->readDir(getcwd())));
    }

    /**
     * @param  string $dirPath
     * @return array
     */
    protected function readDir(string $dirPath): array
    {
        $dirPath = preg_replace('~\/+~', '/', $dirPath . '/');
        $directory = new RecursiveDirectoryIterator($dirPath, FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory);

        $files = [];
        foreach ($iterator as $path) {
            $files[] = preg_replace('~/+~i', '/', $path->getPathname());
        }

        return $files;
    }
}
