<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\Node\ResultTypeNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\ResultTypeNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\ResultTypeNodeHandler
 * @uses   \Avronaut\Node\ResultTypeNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class ResultTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisitVoid(): void
    {
        $node = new ResultTypeNode(true);
        $writer = new BufferedWriter();
        $handler = new ResultTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('void', $writer->getBuffer());
    }

    public function testVisitNonVoid(): void
    {
        $node = new ResultTypeNode(false);
        $writer = new BufferedWriter();
        $handler = new ResultTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertEquals('', $writer->getBuffer());
    }
}
