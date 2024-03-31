<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Load;

use Avronaut\AvroFileMap;
use Avronaut\AvroFilePath;
use Avronaut\Load\FileLoader;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Load\FileLoader
 * @uses   \Avronaut\AvroFilePath
 */
class FileLoaderTest extends AvroTestCase
{
    public function testThrowWithFileName(): void
    {
        $fileName = AvroFilePath::fromString('foo');
        $loader = new BogusFileLoader();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/bar/');

        $loader->throwWithFileName(new \Exception('bar'), $fileName);
    }
}

// phpcs:ignore
class BogusFileLoader extends FileLoader
{
    public function load(AvroFilePath $filePath): AvroFileMap
    {
        return new AvroFileMap();
    }

    public function open(AvroFilePath $fileName): mixed
    {
        return parent::open($fileName);
    }

    public function close(mixed $stream): void
    {
        parent::close($stream);
    }

    public function throwWithFileName(\Exception $e, AvroFilePath $fileName): never
    {
        parent::throwWithFileName($e, $fileName);
    }
}
