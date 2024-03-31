<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\AvroReference;
use Avronaut\Node\ReferenceTypeNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\ReferenceTypeNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\ReferenceTypeNodeHandler
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\AvroReference
 * @uses   \Avronaut\Node\ReferenceTypeNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class ReferenceTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new ReferenceTypeNode(AvroReference::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new ReferenceTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('foo', $writer->getBuffer());
    }
}
