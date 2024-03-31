<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tree;

use Avronaut\Visitable;

interface Node extends Visitable
{
    /** @throws \RuntimeException when node already has a parent. */
    public function addNode(Node|null ...$nodes): Node;

    public function parentNode(): Node|null;

    /** @return Node[] */
    public function childNodes(): array;

    public function prevNode(): Node|null;

    public function nextNode(): Node|null;

    public function nodeAt(int $i): Node|null;

    public function nodeIndex(): int;

    public function nodeCount(): int;
}
