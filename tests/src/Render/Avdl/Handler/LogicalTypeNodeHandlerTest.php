<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\Node\LogicalTypeNode;
use Avronaut\Node\TypeNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Tree\Properties;
use Avronaut\Tree\Property;
use Avronaut\Type\LogicalType;
use Avronaut\Render\Avdl\Handler\LogicalTypeNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\LogicalTypeNodeHandler
 * @uses   \Avronaut\Node\LogicalTypeNode
 * @uses   \Avronaut\Node\TypeNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\Property
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class LogicalTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $prop = Properties::fromArray([
            'foo' => Property::fromNameValue('foo', 1),
            'bar' => Property::fromNameValue('bar', "\33"),
        ]);

        $node = new LogicalTypeNode(LogicalType::DATE, $prop);
        $writer = new BufferedWriter();
        $handler = new LogicalTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('@foo(1) @bar("\u001b") date', $writer->getBuffer());

        $this->assertTrue($handler->visit(new TypeNode()));
    }
}
