<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

use Avronaut\Shared\Position;

class JsonValueNode extends JsonNode implements \JsonSerializable
{
    public function __construct(
        private readonly bool|null|float|string $value,
        Position $position
    ) {
        parent::__construct($position);
    }

    public function getValue(): bool|null|float|string
    {
        return $this->value;
    }

    public function jsonSerialize(): bool|null|float|string
    {
        return $this->getValue();
    }
}
