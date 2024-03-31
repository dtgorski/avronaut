<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Shared;

/**
 * @template K of array-key
 * @template V of mixed
 * @implements \IteratorAggregate<K, V>
 */
class EntityMap implements \IteratorAggregate
{
    /** @psalm-suppress UnsafeInstantiation */
    public static function fromEmpty(): static
    {
        return new static([]);
    }

    /** @psalm-suppress UnsafeInstantiation */
    public static function fromKeyValue(array $elements): static
    {
        return new static($elements);
    }

    protected function __construct(private readonly array $elements)
    {
    }

    /** @param K $key */
    public function has(mixed $key): bool
    {
        return array_key_exists($key, $this->elements);
    }

    /**
     * @param K $key
     * @return mixed
     */
    public function get(mixed $key): mixed
    {
        return $this->has($key) ? $this->elements[$key] : null;
    }

    /** @return V[] */
    public function asArray(): array
    {
        return $this->elements;
    }

    public function isEmpty(): bool
    {
        return $this->size() == 0;
    }

    public function size(): int
    {
        return sizeof($this->elements);
    }

    /** @return \ArrayIterator<K, V> */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->elements);
    }
}
