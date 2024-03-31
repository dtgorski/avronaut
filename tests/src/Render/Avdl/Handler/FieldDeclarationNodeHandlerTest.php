<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\Node\FieldDeclarationNode;
use Avronaut\Node\TypeNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\FieldDeclarationNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\FieldDeclarationNodeHandler
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\FieldDeclarationNode
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
class FieldDeclarationNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new FieldDeclarationNode();
        $writer = new BufferedWriter();
        $handler = new FieldDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('', $writer->getBuffer());

        $this->assertTrue($handler->visit(new TypeNode()));
    }

    public function testLeave(): void
    {
        $node = new FieldDeclarationNode();
        $writer = new BufferedWriter();
        $handler = new FieldDeclarationNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals(";\n", $writer->getBuffer());
    }
}
