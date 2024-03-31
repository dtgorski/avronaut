<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render;

use Avronaut\Visitable;
use Avronaut\Visitor;

interface NodeHandler extends Visitor
{
    public function canHandle(Visitable $node): bool;
}
