<?php

namespace spec\Swagger;

use Closure;
use Swagger\Template;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Zend\Cache\Storage\StorageInterface;

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

        $storage->getItem('model')->willReturn("return function() { return'template'; };");
        $storage->getItem('model')->shouldBeCalled();

        $this->render('model')->shouldBeString();
    }

    public function it_can_render_from_cache(StorageInterface $storage)
    {
        $storage->setItem()->shouldNotBeCalled();
        $storage->hasItem('model')->willReturn(true);
        $storage->hasItem('model')->shouldBeCalled();

        $storage->getMetadata('model')->willReturn(['mtime' => time()]);
        $storage->getMetadata('model')->shouldBeCalled();

        $storage->getItem('model')->willReturn("return function() { return'template'; };");
        $storage->getItem('model')->shouldBeCalled();

        $this->render('model')->shouldBeString();
    }

    public function it_cant_render_with_an_invalid_template(StorageInterface $storage)
    {
        $this->shouldThrow('\Exception')->during('render', ['test']);
    }
}
