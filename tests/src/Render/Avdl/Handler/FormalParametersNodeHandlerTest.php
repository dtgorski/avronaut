<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\AvroName;
use Avronaut\Node\FormalParametersNode;
use Avronaut\Node\MessageDeclarationNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\FormalParametersNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\FormalParametersNodeHandler
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\FormalParameterNode
 * @uses   \Avronaut\Node\MessageDeclarationNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class FormalParametersNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $parent = new MessageDeclarationNode(AvroName::fromString('foo'));
        $node = new FormalParametersNode();
        $parent->addNode($node);

        $writer = new BufferedWriter();
        $handler = new FormalParametersNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals(' foo(', $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new FormalParametersNode();
        $writer = new BufferedWriter();
        $handler = new FormalParametersNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals(')', $writer->getBuffer());
    }
}
