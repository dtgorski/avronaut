<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests;

use PHPUnit\Framework\TestCase;

class AvroTestCase extends TestCase
{
    /** @return resource */
    protected function openStream(string $filename)
    {
        return fopen($filename, 'r');
    }

    /** @return resource */
    protected function createStream(string $source)
    {
        $stream = fopen('php://memory', 'rw');
        fwrite($stream, $source);
        fseek($stream, 0);
        return $stream;
    }

    /** @param $stream resource */
    protected function closeStream(mixed $stream): void
    {
        if (is_resource($stream)) {
            fclose($stream);
        }
    }
}
