<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\AvroName;
use Avronaut\Node\EnumConstantNode;
use Avronaut\Node\EnumDeclarationNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\EnumConstantNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\EnumConstantNodeHandler
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\EnumConstantNode
 * @uses   \Avronaut\Node\EnumDeclarationNode
 * @uses   \Avronaut\Node\NamedDeclarationNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class EnumConstantNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new EnumConstantNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new EnumConstantNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('foo', $writer->getBuffer());
    }

    public function testLeaveWithoutSiblingNodes(): void
    {
        $node = new EnumConstantNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new EnumConstantNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals("\n", $writer->getBuffer());
    }

    public function testLeaveWithSiblingNodes(): void
    {
        $node = new EnumDeclarationNode(AvroName::fromString('foo'), 'bar');
        $node->addNode(new EnumConstantNode(AvroName::fromString('foo')));
        $node->addNode(new EnumConstantNode(AvroName::fromString('bar')));

        $writer = new BufferedWriter();
        $handler = new EnumConstantNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->leave($node->nodeAt(0));

        $this->assertEquals(",\n", $writer->getBuffer());
    }
}
