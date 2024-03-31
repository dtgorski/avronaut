<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

use Avronaut\AvroName;
use Avronaut\Tree\Properties;

class EnumDeclarationNode extends NamedDeclarationNode
{
    public function __construct(
        AvroName $name,
        private readonly string $default,
        ?Properties $properties = null
    ) {
        parent::__construct($name, $properties);
    }

    public function getDefault(): string
    {
        return $this->default;
    }
}
