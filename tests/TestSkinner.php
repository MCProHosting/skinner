<?php

use Mcprohosting\Skinner\Skin;
use Mcprohosting\Skinner\Skinner;
use Mcprohosting\Skinner\ImageProvider;
use Mcprohosting\Skinner\Fetcher;

class MinotarCacheAdapterTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testSkinnerProvidesSkin()
    {
        $skin = Skinner::user('connor4312');

        $this->assertInstanceOf('\Mcprohosting\Skinner\Skin', $skin);
    }

    public function testSkinnerFailsOnBadUsername()
    {
        $this->setExpectedException('\Mcprohosting\Skinner\Exceptions\InvalidUsernameException');
        $skin = Skinner::user('false"username!');
    }

    public function testSkinRetrieves()
    {
        $fetcher = Mockery::mock('\Mcprohosting\Skinner\Fetcher');
        $provider = Mockery::mock('\Mcprohosting\Skinner\ImageProvider');
        $fetcher->shouldReceive('download')->with('connor4312')->andReturn('foo');
        $provider->shouldReceive('make')->with('foo')->andReturn('bar');

        $skin = (new Skin('connor4312', $fetcher, $provider))->skin();
        $this->assertEquals('bar', $skin->data);
    }

    public function testCropsHead()
    {
        $m = Mockery::mock('\Intervention\Image');
        $m->shouldReceive('crop')->with(8, 8, 8, 8)->andReturn('bar');
        $fetcher = Mockery::mock('\Mcprohosting\Skinner\Fetcher');
        $provider = Mockery::mock('\Mcprohosting\Skinner\ImageProvider');
        $fetcher->shouldReceive('download')->with('connor4312')->andReturn('foo');
        $provider->shouldReceive('make')->with('foo')->andReturn($m);

        $skin = (new Skin('connor4312', $fetcher, $provider))->head();
        $this->assertEquals('bar', $skin->data);
    }

    public function testEncodes()
    {
        $m = Mockery::mock('\Intervention\Image');
        $m->shouldReceive('encode')->with('foo', 42)->andReturn('bar');
        $fetcher = Mockery::mock('\Mcprohosting\Skinner\Fetcher');
        $provider = Mockery::mock('\Mcprohosting\Skinner\ImageProvider');

        $skin = new Skin('connor4312', $fetcher, $provider);
        $skin->data = $m;
        $this->assertEquals('bar', $skin->encode('foo', 42));
    }

    public function testEncodesOnString()
    {
        $m = Mockery::mock('\Intervention\Image');
        $m->shouldReceive('encode')->andReturn('bar');
        $fetcher = Mockery::mock('\Mcprohosting\Skinner\Fetcher');
        $provider = Mockery::mock('\Mcprohosting\Skinner\ImageProvider');

        $skin = new Skin('connor4312', $fetcher, $provider);
        $skin->data = $m;
        $this->assertEquals('bar', $skin->__toString());
    }

    public function testPassesCallsToImage()
    {
        $m = Mockery::mock('\Intervention\Image');
        $m->shouldReceive('foo')->with('argument')->andReturn('bar');
        $fetcher = Mockery::mock('\Mcprohosting\Skinner\Fetcher');
        $provider = Mockery::mock('\Mcprohosting\Skinner\ImageProvider');

        $skin = new Skin('connor4312', $fetcher, $provider);
        $skin->data = $m;
        $this->assertEquals('bar', $skin->foo('argument'));
    }

    public function testImageProvider()
    {
        $provider = new ImageProvider;
        $this->assertInstanceOf('\Intervention\Image', $provider->make(null));
    }

    public function testFetcher()
    {
        $fetcher = new Fetcher;

        $out = $fetcher->download('connor431');
        $this->assertTrue(!! $out);
    }
}