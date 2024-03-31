<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tree;

class Property implements \JsonSerializable
{
    public function __construct(
        private readonly string $name,
        private readonly mixed $json
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getJson(): mixed
    {
        return $this->json;
    }

    public function jsonSerialize(): array
    {
        return [$this->getName() => $this->getJson()];
    }
}
