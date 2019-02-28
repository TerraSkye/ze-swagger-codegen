<?php

namespace spec\Swagger;

use Swagger\Exception\CodegenException;
use Swagger\Template;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Zend\Cache\Storage\StorageInterface;
use org\bovigo\vfs\vfsStream;

class TemplateSpec extends ObjectBehavior
{
    public function let(StorageInterface $storage)
    {
        $this->beConstructedWith($storage);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Template::class);
    }

    public function it_can_render_with_invalid_cache(StorageInterface $storage)
    {
        $storage->setItem('model', Argument::type('string'))->willReturn(true);
        $storage->setItem('model', Argument::type('string'))->shouldBeCalled();
        $storage->hasItem('model')->willReturn(false);
        $storage->hasItem('model')->shouldBeCalled();

        $storage->getMetadata('model')->shouldNotBeCalled();

        $storage->getItem('model')->willReturn("return function() { return'template'; };");
        $storage->getItem('model')->shouldBeCalled();

        $this->render('model')->shouldBeString();

        $this->shouldNotThrow(new CodegenException('Template not found'))->during('render', ['model']);
    }

    public function it_can_render_from_cache(StorageInterface $storage)
    {
        $storage->setItem('model', Argument::type('string'))->shouldNotBeCalled();
        $storage->hasItem('model')->willReturn(true);
        $storage->hasItem('model')->shouldBeCalled();

        $storage->getMetadata('model')->willReturn(['mtime' => time()]);
        $storage->getMetadata('model')->shouldBeCalled();

        $storage->getItem('model')->willReturn("return function() { return'template'; };");
        $storage->getItem('model')->shouldBeCalled();

        $this->render('model')->shouldBeString();

        $this->shouldNotThrow(new CodegenException('Template not found'))->during('render', ['model']);
    }

    public function it_must_render_without_cache_because_of_changed_template(StorageInterface $storage)
    {
        $vfsProjectRoot = vfsStream::setup('templateFolder');
        vfsStream::copyFromFileSystem(realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR), $vfsProjectRoot);
        $templateFolder = vfsStream::url('templateFolder');

        $this->setTemplateFolder($templateFolder);

        $storage->hasItem('model')->willReturn(true);
        $storage->hasItem('model')->shouldBeCalled();

        $mtime = filemtime($templateFolder . '/model.hbs');

        $storage->getMetadata('model')->willReturn(['mtime' => $mtime - 1]);
        $storage->getMetadata('model')->shouldBeCalled();

        $storage->setItem('model', Argument::type('string'))->shouldBeCalled();

        $storage->getItem('model')->willReturn("return function() { return'template'; };");
        $storage->getItem('model')->shouldBeCalled();

        $this->render('model')->shouldBeString();
    }

    public function it_cant_render_with_an_invalid_template(StorageInterface $storage)
    {
        $this->shouldThrow(new CodegenException('Template not found'))->during('render', ['test']);
    }

    public function it_must_render_from_cache_when_template_isnt_changed(StorageInterface $storage)
    {
        $vfsTemplateFolder = vfsStream::setup('templateFolder');
        vfsStream::copyFromFileSystem(realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR), $vfsTemplateFolder);
        $templateFolder = vfsStream::url('templateFolder');

        $this->setTemplateFolder($templateFolder);

        $storage->hasItem('model')->willReturn(true);
        $storage->hasItem('model')->shouldBeCalled();

        $mtime = filemtime($templateFolder . '/model.hbs');

        $storage->getMetadata('model')->willReturn(['mtime' => $mtime]);
        $storage->getMetadata('model')->shouldBeCalled();

        $storage->setItem('model', Argument::type('string'))->shouldNotBeCalled();

        $storage->getItem('model')->willReturn("return function() { return'template'; };");
        $storage->getItem('model')->shouldBeCalled();

        $this->render('model')->shouldBeString();

        $storage->getMetadata('model')->willReturn(['mtime' => $mtime + 1]);

        $this->render('model')->shouldBeString();
    }
}
