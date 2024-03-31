<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Tree;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Tree\AstNode;
use Avronaut\Tree\Properties;
use Avronaut\Visitable;
use Avronaut\Visitor;

/**
 * @covers \Avronaut\Tree\AstNode
 * @covers \Avronaut\Tree\TreeNode
 *
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\Properties
 */
class AstNodeTest extends AvroTestCase
{
    public function testAccept(): void
    {
        $node = (new AstTestNode('A'))
            ->addNode((new AstTestNode('B'))
                ->addNode(new AstTestNode('C')))->addNode((new AstTestNode('D'))
                ->addNode(new AstTestNode('E'))
                ->addNode(new AstTestNode('F')))->addNode((new AstTestNode('G'))
                ->addNode(new AstTestNode('H'))
                ->addNode(new AstTestNode('I'))
                ->addNode(new AstTestNode('J')));

        $visitor = new AstTestVisitor();
        $this->assertSame('', $visitor->thread);
        $node->accept($visitor);
        $this->assertEquals('ABCDEFGHIJ', $visitor->thread);
    }

    public function testParentChildSibling(): void
    {
        $A = (new AstTestNode())
            ->addNode(($B = new AstTestNode())
                ->addNode($C = new AstTestNode()))->addNode(($D = new AstTestNode())
                ->addNode($E = new AstTestNode())
                ->addNode($F = new AstTestNode()))->addNode(($G = new AstTestNode())
                ->addNode($H = new AstTestNode())
                ->addNode($I = new AstTestNode())
                ->addNode($J = new AstTestNode()));

        // getParent()
        $this->assertSame($A, $B->parentNode());
        $this->assertSame($B, $C->parentNode());

        // getChildCount()
        $this->assertSame(3, $A->nodeCount());
        $this->assertSame(1, $B->nodeCount());
        $this->assertSame(2, $D->nodeCount());
        $this->assertSame(3, $G->nodeCount());

        // getChildIndex()
        $this->assertEquals(-1, $A->nodeIndex());
        $this->assertSame(0, $B->nodeIndex());
        $this->assertSame(1, $D->nodeIndex());
        $this->assertSame(0, $C->nodeIndex());
        $this->assertSame(0, $E->nodeIndex());
        $this->assertSame(1, $F->nodeIndex());
        $this->assertSame(0, $H->nodeIndex());
        $this->assertSame(1, $I->nodeIndex());
        $this->assertSame(2, $J->nodeIndex());

        // getPrevSibling()
        $this->assertSame(null, $A->prevNode());
        $this->assertSame(null, $B->prevNode());
        $this->assertSame(null, $C->prevNode());
        $this->assertSame(null, $E->prevNode());
        $this->assertSame(null, $H->prevNode());
        $this->assertSame($E, $F->prevNode());
        $this->assertSame($H, $I->prevNode());
        $this->assertSame($I, $J->prevNode());

        // getNextSibling()
        $this->assertSame(null, $A->nextNode());
        $this->assertSame(null, $C->nextNode());
        $this->assertSame(null, $F->nextNode());
        $this->assertSame(null, $J->nextNode());
        $this->assertSame($I, $H->nextNode());
        $this->assertSame($J, $I->nextNode());
        $this->assertSame($D, $B->nextNode());
        $this->assertSame($G, $D->nextNode());
    }

    public function testAddingNullNodes(): void
    {
        $node = new AstTestNode();
        $this->assertSame(0, $node->nodeCount());
        $node->addNode(null);
        $this->assertSame(0, $node->nodeCount());
    }

    public function testAddingNodesWithParents(): void
    {
        $this->expectException(\RuntimeException::class);
        (new AstTestNode())->addNode($child = new AstTestNode());
        $other = new AstTestNode();
        $other->addNode($child);
    }

    public function testNodesProperties(): void
    {
        $properties = Properties::fromEmpty();
        $node = new class ($properties) extends AstNode {
        };
        $this->assertSame($properties, $node->getProperties());
    }
}

// phpcs:ignore
class AstTestNode extends AstNode
{
    public function __construct(readonly string $name = '')
    {
        parent::__construct();
    }
}

// phpcs:ignore
class AstTestVisitor implements Visitor
{
    public string $thread = '';

    public function visit(Visitable $node): bool
    {
        /** @psalm-suppress NoInterfaceProperties */
        $this->thread .= $node->name;
        return true;
    }

    public function leave(Visitable $node): void
    {
    }
}
