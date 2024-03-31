<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Avdl\Handler;

use Avronaut\Node\FormalParametersNode;
use Avronaut\Node\MessageDeclarationNode;
use Avronaut\Render\Avdl\HandlerAbstract;
use Avronaut\Visitable;

/** @internal */
class FormalParametersNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof FormalParametersNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof FormalParametersNode) {
            parent::visit($node);

            if (($message = $node->parentNode()) && $message instanceof MessageDeclarationNode) {
                $this->write(' ', $this->guardKeyword($message->getName()->getValue()), '(');
            }
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        if ($node instanceof FormalParametersNode) {
            $this->write(')');

            parent::leave($node);
        }
    }
}
