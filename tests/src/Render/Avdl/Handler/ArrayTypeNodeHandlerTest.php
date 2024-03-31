<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\Node\ArrayTypeNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\ArrayTypeNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\ArrayTypeNodeHandler
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class ArrayTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new ArrayTypeNode();
        $writer = new BufferedWriter();
        $handler = new ArrayTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('array<', $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new ArrayTypeNode();
        $writer = new BufferedWriter();
        $handler = new ArrayTypeNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals('>', $writer->getBuffer());
    }
}
