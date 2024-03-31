<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests;

use Avronaut\AvroFilePath;

/**
 * @covers \Avronaut\AvroFilePath
 */
class AvroFilePathTest extends AvroTestCase
{
    public function testFromString(): void
    {
        $path = AvroFilePath::fromString(__FILE__);
        $this->assertEquals(basename(__FILE__), $path->getBasename());
        $this->assertEquals(dirname(__FILE__), $path->getDirname());
        $this->assertEquals(__FILE__, $path->getPath());
        $this->assertEquals('php', $path->getExtension());

        $path = AvroFilePath::fromString(__FILE__);
        $this->assertFalse($path->isDirectory());

        $path = AvroFilePath::fromString('/');
        $this->assertTrue($path->isDirectory());
    }

    public function testGetExtension(): void
    {
        $path = AvroFilePath::fromString('');
        $this->assertEquals('', $path->getExtension());

        $path = AvroFilePath::fromString('/');
        $this->assertEquals('', $path->getExtension());

        $path = AvroFilePath::fromString('foo/bar/');
        $this->assertEquals('', $path->getExtension());
    }
}
