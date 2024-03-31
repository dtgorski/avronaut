<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\Node\PrimitiveTypeNode;
use Avronaut\Node\TypeNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Type\PrimitiveType;
use Avronaut\Render\Avdl\Handler\PrimitiveTypeNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\PrimitiveTypeNodeHandler
 * @uses   \Avronaut\Node\PrimitiveTypeNode
 * @uses   \Avronaut\Node\TypeNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class PrimitiveTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new PrimitiveTypeNode(PrimitiveType::DOUBLE);
        $writer = new BufferedWriter();
        $handler = new PrimitiveTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('double', $writer->getBuffer());

        $this->assertTrue($handler->visit(new TypeNode()));
    }
}
