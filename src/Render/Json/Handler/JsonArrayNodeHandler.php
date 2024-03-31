<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Json\Handler;

use Avronaut\Node\JsonArrayNode;
use Avronaut\Render\Json\HandlerAbstract;
use Avronaut\Visitable;

/** @internal */
class JsonArrayNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof JsonArrayNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof JsonArrayNode) {
            parent::visit($node);

            if ($node->prevNode()) {
                $this->write(', ');
            }
            $this->write('[');
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        if ($node instanceof JsonArrayNode) {
            parent::leave($node);

            $this->write(']');
        }
    }
}
