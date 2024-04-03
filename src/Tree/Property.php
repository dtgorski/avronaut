<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tree;

class Property implements \JsonSerializable
{
    /** @psalm-suppress UnsafeInstantiation */
    public static function fromNameValue(string $name, mixed $value): static
    {
        return new static($name, $value);
    }

    protected function __construct(
        private readonly string $name,
        private readonly mixed $value
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function jsonSerialize(): array
    {
        return [$this->getName() => $this->getValue()];
    }
}
