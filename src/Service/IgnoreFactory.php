<?php

declare(strict_types=1);

namespace Swagger\Service;

use Swagger\Ignore;

class IgnoreFactory
{
    public function __invoke(): Ignore
    {
        return new Ignore(
            $this->findIgnoreFiles()
        );
    }

    protected function findIgnoreFiles()
    {
        return preg_grep('~\.swagger-codegen-ignore\b~i', array_values($this->readDir(getcwd())));
    }

    protected function readDir($dir)
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
