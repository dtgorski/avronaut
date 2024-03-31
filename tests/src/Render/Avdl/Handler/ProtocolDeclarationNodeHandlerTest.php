<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\AvroName;
use Avronaut\Node\ProtocolDeclarationNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\ProtocolDeclarationNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\ProtocolDeclarationNodeHandler
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\NamedDeclarationNode
 * @uses   \Avronaut\Node\ProtocolDeclarationNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class ProtocolDeclarationNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new ProtocolDeclarationNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new ProtocolDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals("protocol foo {\n", $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new ProtocolDeclarationNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new ProtocolDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);
        $handler->leave($node);

        $this->assertEquals("protocol foo {\n}\n", $writer->getBuffer());
    }
}
