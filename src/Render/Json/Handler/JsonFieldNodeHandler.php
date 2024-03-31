<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Json\Handler;

use Avronaut\Node\JsonFieldNode;
use Avronaut\Render\Json\HandlerAbstract;
use Avronaut\Visitable;

/** @internal */
class JsonFieldNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof JsonFieldNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof JsonFieldNode) {
            parent::visit($node);

            if ($node->prevNode()) {
                $this->write(', ');
            }
            $this->write('"', $node->getName()->getValue(), '":');
        }
        return true;
    }
}
