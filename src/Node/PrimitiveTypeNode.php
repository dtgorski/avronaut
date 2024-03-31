<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

use Avronaut\Tree\AstNode;
use Avronaut\Tree\Properties;
use Avronaut\Type\PrimitiveType;

class PrimitiveTypeNode extends AstNode
{
    public function __construct(
        private readonly PrimitiveType $type,
        ?Properties $properties = null
    ) {
        parent::__construct($properties);
    }

    public function getType(): PrimitiveType
    {
        return $this->type;
    }
}
