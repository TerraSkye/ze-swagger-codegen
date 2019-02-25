<?php

declare(strict_types=1);

namespace Swagger\Service;

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
     * @param  string $dir
     * @return array
     */
    protected function readDir(string $dir): array
    {
        $files = array();
        $dir = preg_replace('~\/+~', '/', $dir . '/');
        $all  = scandir($dir);
        foreach ($all as $path) {
            if ($path !== '.' && $path !== '..') {
                $path = $dir . '/' . $path;
                $path = preg_replace('~\/+~', '/', $path);
                $path = realpath($path);
                if (is_dir($path)) {
                    $files = array_merge($files, $this->readDir($path));
                }
                $files[] = preg_replace('~/+~i', '/', $path);
            }
        }
        return $files;
    }
}
