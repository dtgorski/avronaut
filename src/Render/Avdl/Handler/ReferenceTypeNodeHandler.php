<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Avdl\Handler;

use Avronaut\Node\ReferenceTypeNode;
use Avronaut\Render\Avdl\HandlerAbstract;
use Avronaut\Visitable;

/** @internal */
class ReferenceTypeNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof ReferenceTypeNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof ReferenceTypeNode) {
            parent::visit($node);

            $this->writePropertiesSingleLine($node->getProperties());
            $this->write($node->getReference()->getQualifiedName());
        }
        return true;
    }
}
