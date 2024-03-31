<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tree;

use Avronaut\Shared\EntityMap;

/** @extends EntityMap<string, Property> */
class Properties extends EntityMap implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return array_values($this->asArray());
    }
}
