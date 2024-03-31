<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut;

class AvroFilePath
{
    public static function fromString(string $path): AvroFilePath
    {
        return new self($path);
    }

    private function __construct(private readonly string $path)
    {
    }

    public function isDirectory(): bool
    {
        return str_ends_with($this->path, '/');
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getExtension(): string
    {
        if ($this->isDirectory()) {
            return '';
        }
        $parts = preg_split('/\./', $this->getBasename());
        return is_array($parts) && sizeof($parts) > 0 ? strtolower(array_pop($parts)) : '';
    }

    public function getBasename(): string
    {
        return basename($this->path);
    }

    public function getDirname(): string
    {
        return dirname($this->path);
    }
}
