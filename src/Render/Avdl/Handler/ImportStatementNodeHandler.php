<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Avdl\Handler;

use Avronaut\Node\ImportStatementNode;
use Avronaut\Render\Avdl\HandlerAbstract;
use Avronaut\Visitable;

/** @internal */
class ImportStatementNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof ImportStatementNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof ImportStatementNode) {
            parent::visit($node);

            if (!$node->prevNode() instanceof ImportStatementNode) {
                $this->writeln();
            }
            $name = $node->getType()->value;
            $path = $node->getPath();
            $this->writeln($this->indent(), 'import ', $name, ' "', $path, '";');

            return false;
        }
        return true;
    }
}
