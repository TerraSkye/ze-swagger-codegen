<?php

namespace Swagger\Generator;

use Zend\Filter\Word\UnderscoreToCamelCase;

abstract class AbstractGenerator
{
    /**
     * @var UnderscoreToCamelCase
     */
    protected $camelCaseFilter;

    /**
     * @param  string $tag
     *
     * @return string
     */
    protected function toModelName(string $tag): string
    {
        $tag = preg_replace('/[\\}]/', '', $tag);
        $tag = preg_replace('/\\]/', '', $tag);
        $tag = preg_replace('/[^\\w\\\\]+/', '_', $tag);

        return $this->getCamelCaseFilter()->filter($tag);
    }

    /**
     * @return UnderscoreToCamelCase
     */
    protected function getCamelCaseFilter(): UnderscoreToCamelCase
    {
        if (!$this->camelCaseFilter) {
            $this->camelCaseFilter = new UnderscoreToCamelCase();
        }

        return $this->camelCaseFilter;
    }

    /**
     * @param  string $path
     * @param  string $contents
     */
    protected function writeFile(string $path, string $contents)
    {
        $folder = str_replace(basename($path), '', $path);

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        file_put_contents($path, $contents);
    }
}
