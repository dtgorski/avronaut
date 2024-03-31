<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\AvroName;
use Avronaut\Node\FormalParameterNode;
use Avronaut\Node\JsonValueNode;
use Avronaut\Node\VariableDeclaratorNode;
use Avronaut\Shared\Position;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\VariableDeclaratorNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\VariableDeclaratorNodeHandler
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Node\JsonValueNode
 * @uses   \Avronaut\Node\VariableDeclaratorNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class VariableDeclaratorNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithValue(): void
    {
        $node = new VariableDeclaratorNode(AvroName::fromString('foo'));
        $node->addNode(new JsonValueNode(true, new Position(0, 0)));
        $writer = new BufferedWriter();
        $handler = new VariableDeclaratorNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals(' foo = ', $writer->getBuffer());
    }

    public function testVisitWithoutValue(): void
    {
        $node = new VariableDeclaratorNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new VariableDeclaratorNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertEquals(' foo', $writer->getBuffer());
    }

    public function testLeaveWithSiblingNodes(): void
    {
        $node = new FormalParameterNode();
        $node->addNode(new VariableDeclaratorNode(AvroName::fromString('foo')));
        $node->addNode(new VariableDeclaratorNode(AvroName::fromString('bar')));

        $writer = new BufferedWriter();
        $handler = new VariableDeclaratorNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->leave($node->nodeAt(0));

        $this->assertEquals(',', $writer->getBuffer());
    }

    public function testLeaveWithoutSiblingNodes(): void
    {
        $node = new VariableDeclaratorNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new VariableDeclaratorNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals('', $writer->getBuffer());
    }
}
