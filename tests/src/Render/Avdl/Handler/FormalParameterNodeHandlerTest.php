<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\Node\FormalParameterNode;
use Avronaut\Node\FormalParametersNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\FormalParameterNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\FormalParameterNodeHandler
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\Node\FormalParameterNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class FormalParameterNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithoutSiblingNodes(): void
    {
        $node = new FormalParameterNode();
        $writer = new BufferedWriter();
        $handler = new FormalParameterNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('', $writer->getBuffer());
    }

    public function testVisitWithSiblingNodes(): void
    {
        $node = new FormalParametersNode();
        $node->addNode(new FormalParameterNode());
        $node->addNode(new FormalParameterNode());

        $writer = new BufferedWriter();
        $handler = new FormalParameterNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(1));

        $this->assertEquals(', ', $writer->getBuffer());
    }
}
