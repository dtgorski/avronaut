<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Avdl\Handler;

use Avronaut\Node\VariableDeclaratorNode;
use Avronaut\Render\Avdl\HandlerAbstract;
use Avronaut\Visitable;

/** @internal */
class VariableDeclaratorNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof VariableDeclaratorNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof VariableDeclaratorNode) {
            parent::visit($node);

            $this->write(' ');
            $this->writePropertiesSingleLine($node->getProperties());
            $this->write($this->guardKeyword($node->getName()->getValue()));

            if ($node->nodeCount()) {
                $this->write(' = ');
            }
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        if ($node instanceof VariableDeclaratorNode) {
            if ($node->nextNode()) {
                $this->write(',');
            }

            parent::leave($node);
        }
    }
}
