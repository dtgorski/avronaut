<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Json\Handler;

use Avronaut\Node\JsonArrayNode;
use Avronaut\Node\JsonValueNode;
use Avronaut\Node\TypeNode;
use Avronaut\Shared\Position;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Json\Handler\JsonValueNodeHandler;
use Avronaut\Render\Json\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Json\Handler\JsonValueNodeHandler
 * @uses   \Avronaut\Node\JsonArrayNode
 * @uses   \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Node\JsonValueNode
 * @uses   \Avronaut\Node\TypeNode
 * @uses   \Avronaut\Render\Json\HandlerAbstract
 * @uses   \Avronaut\Render\Json\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class JsonValueNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithoutSiblings(): void
    {
        $writer = new BufferedWriter();
        $handler = new JsonValueNodeHandler(new HandlerContext($writer));

        $node = new JsonValueNode(0.123, new Position(0, 0));
        $writer->clearBuffer();
        $handler->visit($node);
        $this->assertEquals('0.123', $writer->getBuffer());

        $node = new JsonValueNode("\33", new Position(0, 0));
        $writer->clearBuffer();
        $handler->visit($node);
        $this->assertEquals('"\u001b"', $writer->getBuffer());

        $node = new JsonValueNode(null, new Position(0, 0));
        $writer->clearBuffer();
        $handler->visit($node);
        $this->assertEquals('null', $writer->getBuffer());

        $node = new JsonValueNode(true, new Position(0, 0));
        $writer->clearBuffer();
        $handler->visit($node);
        $this->assertEquals('true', $writer->getBuffer());

        $node = new JsonValueNode(false, new Position(0, 0));
        $writer->clearBuffer();
        $handler->visit($node);
        $this->assertEquals('false', $writer->getBuffer());

        $this->assertTrue($handler->canHandle($node));

        $this->assertTrue($handler->visit(new TypeNode()));
    }

    public function testVisitWithSiblings(): void
    {
        $node = new JsonArrayNode(new Position(0, 0));
        $node->addNode(new JsonValueNode(null, new Position(0, 0)));
        $node->addNode(new JsonValueNode(42, new Position(0, 0)));

        $writer = new BufferedWriter();
        $handler = new JsonValueNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(1));

        $this->assertEquals(', 42', $writer->getBuffer());
    }
}
