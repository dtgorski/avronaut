<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Load;

use Avronaut\AvroFilePath;

/**
 * @codeCoverageIgnore
 * @internal
 */
abstract class FileLoader implements Loader
{
    /**
     * @throws \Exception
     * @return resource
     */
    protected function open(AvroFilePath $fileName): mixed
    {
        $path = $fileName->getPath();

        if (!is_readable($path) || is_dir($path)) {
            throw new \Exception(sprintf('unable to read file %s', $path));
        }
        if (!$stream = fopen($path, 'r')) {
            throw new \Exception(sprintf('failed to open file: %s', $path));
        }

        return $stream;
    }

    /** @param mixed $stream */
    protected function close(mixed $stream): void
    {
        if (is_resource($stream)) {
            fclose($stream);
        }
    }

    /** @throws \Exception */
    protected function throwWithFileName(\Exception $e, AvroFilePath $fileName): never
    {
        throw new \Exception(sprintf('%s in file %s', $e->getMessage(), $fileName->getPath()), 0, $e);
    }
}
