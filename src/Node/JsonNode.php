<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

use Avronaut\Shared\Position;
use Avronaut\Tree\AstNode;

abstract class JsonNode extends AstNode
{
    public function __construct(private readonly Position $position = new Position(0, 0))
    {
        parent::__construct();
    }

    public function getPosition(): Position
    {
        return $this->position;
    }
}
