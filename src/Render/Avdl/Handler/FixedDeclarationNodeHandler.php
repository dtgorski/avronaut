<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Avdl\Handler;

use Avronaut\Node\FixedDeclarationNode;
use Avronaut\Render\Avdl\HandlerAbstract;
use Avronaut\Visitable;

/** @internal */
class FixedDeclarationNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof FixedDeclarationNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof FixedDeclarationNode) {
            parent::visit($node);

            $this->write($this->indent());
            $this->write('fixed ', $this->guardKeyword($node->getName()->getValue()));
            $this->writeln('(', $node->getValue(), ');');

            return false;
        }
        return true;
    }
}
