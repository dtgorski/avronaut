<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Avdl\Handler;

use Avronaut\Node\ArrayTypeNode;
use Avronaut\Render\Avdl\HandlerAbstract;
use Avronaut\Visitable;

/** @internal */
class ArrayTypeNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof ArrayTypeNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof ArrayTypeNode) {
            parent::visit($node);

            $this->write('array<');
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        if ($node instanceof ArrayTypeNode) {
            $this->write('>');
            parent::leave($node);
        }
    }
}
