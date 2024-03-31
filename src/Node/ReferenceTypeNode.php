<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

use Avronaut\AvroReference;
use Avronaut\Tree\AstNode;
use Avronaut\Tree\Properties;

class ReferenceTypeNode extends AstNode
{
    public function __construct(
        private readonly AvroReference $reference,
        ?Properties $properties = null
    ) {
        parent::__construct($properties);
    }

    public function getReference(): AvroReference
    {
        return $this->reference;
    }
}
