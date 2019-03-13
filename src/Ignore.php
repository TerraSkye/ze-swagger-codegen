<?php

declare(strict_types=1);

namespace Swagger;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Ignore
{
    /**
     * @var array|string[]
     */
    protected $ignoredFiles = [];

    /**
     * @var array|string[]
     */
    protected $ignoreFiles;

    /**
     * Constructor
     * ---
     * @param array $ignoreFiles
     */
    public function __construct(array $ignoreFiles)
    {
        $this->ignoreFiles = $ignoreFiles;
        $this->parse($ignoreFiles);
    }

    public function parse(array $ignoreFiles)
    {
        foreach ($ignoreFiles as $ignoreFile) {
            $contents = file_get_contents($ignoreFile);
            $path = dirname(realpath($ignoreFile));

            $rules = array();

            //Skip blank lines and comments
            preg_match_all('/^(?!#)(?!\s)(.*)$/m', $contents, $rules);

            $rules = $rules[1];

            foreach ($rules as $rule) {
                if (empty($rule)) {
                    continue;
                }
                $paths = glob($path . DIRECTORY_SEPARATOR . $rule);
                $filePaths = [];
                for ($i = 0; $i < count($paths); $i++) {
                    if (is_dir($paths[$i])) {
                        $dir = new RecursiveDirectoryIterator($paths[$i]);
                        $iterator = new RecursiveIteratorIterator($dir);

                        $files = [];
                        foreach ($iterator as $file) {
                            if (!$file->isDir()) {
                                $files[] = $file->getPathName();
                            }
                        }

                        $filePaths = array_merge($filePaths, $files);
                        continue;
                    }

                    $filePaths[] = $paths[$i];
                }

                $this->ignoredFiles = array_merge($this->ignoredFiles, $filePaths);
            }

            foreach ($rules as $rule) {
                if (strpos($rule, '!') === 0) {// negative rule
                    $paths = glob($path . DIRECTORY_SEPARATOR . substr($rule, 1));

                    $filePaths = [];
                    for ($i = 0; $i < count($paths); $i++) {
                        if (is_dir($paths[$i])) {
                            $dir = new RecursiveDirectoryIterator($paths[$i]);
                            $iterator = new RecursiveIteratorIterator($dir);

                            $files = [];
                            foreach ($iterator as $file) {
                                if (!$file->isDir()) {
                                    $files[] = $file->getPathName();
                                }
                            }

                            $filePaths = array_merge($filePaths, $files);
                            continue;
                        }

                        $filePaths[] = $paths[$i];
                    }

                    $this->ignoredFiles = array_diff($this->ignoredFiles, $filePaths);
                }
            }
        }

        $this->ignoredFiles = array_unique($this->ignoredFiles);
    }

    /**
     * @param  string $file
     *
     * @return bool
     */
    public function isIgnored(string $file): bool
    {
        return in_array($file, $this->ignoredFiles);
    }

    /**
     * @return array
     */
    public function getIgnoreFiles(): array
    {
        return $this->ignoreFiles;
    }
}
