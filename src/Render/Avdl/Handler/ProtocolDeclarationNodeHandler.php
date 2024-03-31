<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Avdl\Handler;

use Avronaut\Node\ProtocolDeclarationNode;
use Avronaut\Render\Avdl\HandlerAbstract;
use Avronaut\Visitable;

/** @internal */
class ProtocolDeclarationNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof ProtocolDeclarationNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof ProtocolDeclarationNode) {
            parent::visit($node);

            $this->writeln('protocol ', $this->guardKeyword($node->getName()->getValue()), ' {');
            $this->stepIn();
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        if ($node instanceof ProtocolDeclarationNode) {
            $this->stepOut();
            $this->writeln($this->indent(), '}');

            parent::leave($node);
        }
    }
}
