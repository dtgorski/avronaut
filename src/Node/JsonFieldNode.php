<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

use Avronaut\AvroName;
use Avronaut\Shared\Position;

class JsonFieldNode extends JsonNode implements \JsonSerializable
{
    public function __construct(
        private readonly AvroName $name,
        Position $position
    ) {
        parent::__construct($position);
    }

    public function getName(): AvroName
    {
        return $this->name;
    }

    public function jsonSerialize(): bool|null|float|string
    {
        return $this->getName()->getValue();
    }
}
