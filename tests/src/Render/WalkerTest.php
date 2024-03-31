<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Tree\AstNode;
use Avronaut\Tree\Node;
use Avronaut\Render\Walker;

/**
 * @covers \Avronaut\Render\Walker
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Node
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class WalkerTest extends AvroTestCase
{
    public function testTraverseNode(): void
    {
        $node = new WalkerTestNode('A');
        $node->addNode(new WalkerTestNode('B'));
        $node->addNode(new WalkerTestNode('C'));

        $thread = '';
        $walker = Walker::fromFunc(
            function (WalkerTestNode $node) use (&$thread): bool {
                $thread .= $node->name;
                return true;
            },
            function (Node $_): void {
            },
        );
        $walker->traverseNode($node);

        $this->assertEquals('ABC', $thread);
    }

    public function testTraverseNodes(): void
    {
        $node = new WalkerTestNode('A');
        $node->addNode(new WalkerTestNode('B'));
        $node->addNode(new WalkerTestNode('C'));

        $thread = '';
        $walker = Walker::fromFunc(
            function (WalkerTestNode $node) use (&$thread): bool {
                $thread .= $node->name;
                return true;
            }
        );
        $walker->traverseNodes($node->childNodes());

        $this->assertEquals('BC', $thread);
    }

    public function testBacktrack(): void
    {
        $nodeA = new WalkerTestNode('A');
        $nodeB = new WalkerTestNode('B');
        $nodeC = new WalkerTestNode('C');
        $nodeD = new WalkerTestNode('D');

        $nodeA->addNode($nodeB);
        $nodeB->addNode($nodeC);
        $nodeB->addNode($nodeD);

        $thread = '';
        $walker = Walker::fromFunc(
            function (WalkerTestNode $node) use (&$thread): bool {
                $thread .= $node->name;
                return true;
            }
        );

        $walker->backtrack($nodeD);

        $this->assertEquals('DBA', $thread);
    }
}

// phpcs:ignore
class WalkerTestNode extends AstNode
{
    public function __construct(readonly string $name = '')
    {
        parent::__construct();
    }
}
