<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\Node\DecimalTypeNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\DecimalTypeNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\DecimalTypeNodeHandler
 * @uses   \Avronaut\Node\DecimalTypeNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class DecimalTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new DecimalTypeNode(2, 1);
        $writer = new BufferedWriter();
        $handler = new DecimalTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('decimal(2, 1)', $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new DecimalTypeNode(2, 1);
        $writer = new BufferedWriter();
        $handler = new DecimalTypeNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals('', $writer->getBuffer());
    }
}
