<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\AvroName;
use Avronaut\Node\FieldDeclarationNode;
use Avronaut\Node\MessageDeclarationNode;
use Avronaut\Node\TypeNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\TypeNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\TypeNodeHandler
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\FieldDeclarationNode
 * @uses   \Avronaut\Node\MessageDeclarationNode
 * @uses   \Avronaut\Node\TypeNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class TypeNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new TypeNode();
        $writer = new BufferedWriter();
        $handler = new TypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('', $writer->getBuffer());
    }

    public function testVisitWithParentDeclaration(): void
    {
        $node = new FieldDeclarationNode();
        $node = $node->addNode(new TypeNode());

        $writer = new BufferedWriter();
        $handler = new TypeNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(0));

        $this->assertEquals('', $writer->getBuffer());
    }

    public function testLeaveWithSiblings(): void
    {
        $node = new MessageDeclarationNode(AvroName::fromString('foo'));
        $node->addNode(new TypeNode());
        $node->addNode(new TypeNode());

        $writer = new BufferedWriter();
        $handler = new TypeNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->leave($node->nodeAt(0));

        $this->assertEquals(', ', $writer->getBuffer());
    }

    public function testLeaveWithoutSiblings(): void
    {
        $node = new TypeNode();
        $writer = new BufferedWriter();
        $handler = new TypeNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals('', $writer->getBuffer());
    }

    public function testLeaveNullable(): void
    {
        $node = new TypeNode(true);
        $writer = new BufferedWriter();
        $handler = new TypeNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals('?', $writer->getBuffer());
    }
}
