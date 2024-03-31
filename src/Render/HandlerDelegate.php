<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render;

use Avronaut\Tree\Node;
use Avronaut\Visitable;
use Avronaut\Visitor;

class HandlerDelegate implements Visitor
{
    /** @param NodeHandler[] $handlers */
    public function __construct(private array $handlers = [])
    {
    }

    public function addHandler(NodeHandler $handler): void
    {
        $this->handlers[] = $handler;
    }

    /** @return NodeHandler[] */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        /** @var Node $node */
        foreach ($this->getHandlers() as $handler) {
            if ($handler->canHandle($node)) {
                return $handler->visit($node);
            }
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        /** @var Node $node */
        foreach ($this->getHandlers() as $handler) {
            if ($handler->canHandle($node)) {
                $handler->leave($node);
                break;
            }
        }
    }
}
