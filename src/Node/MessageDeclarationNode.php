<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

use Avronaut\AvroName;
use Avronaut\Tree\Properties;

class MessageDeclarationNode extends DeclarationNode
{
    public function __construct(
        private readonly AvroName $name,
        ?Properties $properties = null
    ) {
        parent::__construct($properties);
    }

    public function getName(): AvroName
    {
        return $this->name;
    }
}
