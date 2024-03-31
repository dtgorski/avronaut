<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut;

/** @implements \IteratorAggregate<string, Visitable> */
class AvroFileMap implements \IteratorAggregate
{
    /** @psalm-var array<string, Visitable> */
    private array $elements = [];

    public function set(AvroFilePath $sourceFile, Visitable $node): AvroFileMap
    {
        if (!$this->has($sourceFile)) {
            $this->elements[$sourceFile->getPath()] = $node;
        }
        return $this;
    }

    public function has(AvroFilePath $sourceFile): bool
    {
        return array_key_exists($sourceFile->getPath(), $this->asArray());
    }

    public function get(AvroFilePath $sourceFile): ?Visitable
    {
        return $this->has($sourceFile) ? $this->elements[$sourceFile->getPath()] : null;
    }

    /** @return array<string, Visitable> */
    public function asArray(): array
    {
        return $this->elements;
    }

    public function size(): int
    {
        return sizeof($this->elements);
    }

    /** @return \ArrayIterator<string, Visitable> */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->asArray());
    }
}
