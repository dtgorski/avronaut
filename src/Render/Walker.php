<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render;

use Avronaut\Tree\Node;
use Avronaut\Visitable;
use Avronaut\Visitor;

class Walker implements Visitor
{
    public static function fromFunc(\Closure $visitFunc, ?\Closure $leaveFunc = null): Walker
    {
        return new self($visitFunc, $leaveFunc);
    }

    private function __construct(
        private readonly \Closure $visitFunc,
        private readonly ?\Closure $leaveFunc
    ) {
    }

    /**
     * @param Node $node
     * @throws \Exception
     */
    public function traverseNode(Node $node): void
    {
        $node->accept($this);
    }

    /**
     * @param Node[] $nodes
     * @throws \Exception
     */
    public function traverseNodes(array $nodes): void
    {
        foreach ($nodes as $node) {
            $this->traverseNode($node);
        }
    }

    /**
     * @param Node $node
     * @throws \Exception
     */
    public function backtrack(Node $node): void
    {
        $shallowVisit = function (Node $node): bool {
            ($this->visitFunc)($node);
            return false;
        };

        $walker = self::fromFunc($shallowVisit);
        $node->accept($walker);

        while ($node = $node->parentNode()) {
            $node->accept($walker);
        }
    }

    public function visit(Visitable $node): bool
    {
        return !!($this->visitFunc)($node);
    }

    public function leave(Visitable $node): void
    {
        if ($this->leaveFunc) {
            ($this->leaveFunc)($node);
        }
    }
}
