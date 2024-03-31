<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

class JsonArrayNode extends JsonNode implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return $this->childNodes();
    }
}
