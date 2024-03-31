<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\Node\TypeNode;
use Avronaut\Node\UnionTypeNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\UnionTypeNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\UnionTypeNodeHandler
 * @uses   \Avronaut\Node\TypeNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class UnionTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisitEmpty(): void
    {
        $node = new UnionTypeNode();
        $writer = new BufferedWriter();
        $handler = new UnionTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('union {', $writer->getBuffer());
    }

    public function testVisitNonEmpty(): void
    {
        $node = new UnionTypeNode();
        $node->addNode(new TypeNode());

        $writer = new BufferedWriter();
        $handler = new UnionTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertEquals('union { ', $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new UnionTypeNode();
        $writer = new BufferedWriter();
        $handler = new UnionTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);
        $handler->leave($node);

        $this->assertEquals('union { }', $writer->getBuffer());
    }
}
