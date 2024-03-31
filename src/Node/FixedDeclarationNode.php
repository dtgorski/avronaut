<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

use Avronaut\AvroName;
use Avronaut\Tree\Properties;

class FixedDeclarationNode extends NamedDeclarationNode
{
    public function __construct(
        AvroName $name,
        private readonly int $value,
        ?Properties $properties = null
    ) {
        parent::__construct($name, $properties);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
