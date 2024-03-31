<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Avdl\Handler;

use Avronaut\Node\DeclarationNode;
use Avronaut\Node\OnewayStatementNode;
use Avronaut\Node\TypeNode;
use Avronaut\Render\Avdl\HandlerAbstract;
use Avronaut\Visitable;

/** @internal */
class TypeNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof TypeNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof TypeNode) {
            parent::visit($node);

            if ($node->parentNode() instanceof DeclarationNode) {
                if (!$node->nodeAt(0) instanceof OnewayStatementNode) {
                    $this->write($this->indent());
                }
            } else {
                $this->writePropertiesSingleLine($node->getProperties());
            }
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        if ($node instanceof TypeNode) {
            if ($node->isNullable()) {
                $this->write('?');
            }
            if ($node->nextNode() instanceof TypeNode) {
                $this->write(', ');
            }

            parent::leave($node);
        }
    }
}
