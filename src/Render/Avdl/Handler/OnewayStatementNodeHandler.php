<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Avdl\Handler;

use Avronaut\Node\OnewayStatementNode;
use Avronaut\Render\Avdl\HandlerAbstract;
use Avronaut\Visitable;

/** @internal */
class OnewayStatementNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof OnewayStatementNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof OnewayStatementNode) {
            parent::visit($node);

            $this->write(' oneway');

            return false;
        }
        return true;
    }
}
