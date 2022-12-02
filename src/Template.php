<?php

declare(strict_types=1);

namespace Swagger;

use Closure;
use LightnCandy\LightnCandy;
use Swagger\Exception\CodegenException;

use Laminas\Cache\Storage\StorageInterface;

class Template
{
    /**
     * @var string
     */
    protected $templateFolder;

    /**
     * @var StorageInterface
     */
    protected $cache;

    /**
     * @var array|Closure[]
     */
    protected $usedTemplates = [];

    /**
     * Constructor
     * ---
     * @param StorageInterface $cache
     */
    public function __construct(StorageInterface $cache)
    {
        $this->templateFolder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;

        $this->cache = $cache;
    }

    /**
     * @param  string $template
     * @param  array  $variables
     *
     * @return string
     */
    public function render(string $template, array $variables = []): string
    {
        $tplPath = rtrim($this->templateFolder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $template . '.hbs';

        if (!is_file($tplPath)) {
            throw new CodegenException('Template not found');
        }

        if (!$this->cache->hasItem($template) || filemtime($tplPath) > $this->cache->getMetadata($template)['mtime']) {
            $compiledTpl = LightnCandy::compile(file_get_contents($tplPath), [
                'flags' => LightnCandy::FLAG_BESTPERFORMANCE | LightnCandy::FLAG_HANDLEBARS
            ]);

            $this->cache->setItem($template, $compiledTpl);
        }

        if (!isset($this->usedTemplates[$template])) {
            $this->usedTemplates[$template] = eval($this->cache->getItem($template));
        }

        return $this->usedTemplates[$template]($variables);
    }

    /**
     * @param  string $templateFolder
     * @return self
     */
    public function setTemplateFolder(string $templateFolder): self
    {
        $this->templateFolder = $templateFolder;

        return $this;
    }
}
