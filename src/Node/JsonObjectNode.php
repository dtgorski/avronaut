<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

class JsonObjectNode extends JsonNode implements \JsonSerializable
{
    public function jsonSerialize(): object
    {
        $obj = new \stdClass();

        /** @var JsonFieldNode $node */
        foreach ($this->childNodes() as $node) {
            $field = $node->getName()->getValue();
            $obj->$field = $node->nodeAt(0);
        }

        return $obj;
    }
}
