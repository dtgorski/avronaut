<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Json\Handler;

use Avronaut\Node\JsonObjectNode;
use Avronaut\Shared\Position;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Json\Handler\JsonObjectNodeHandler;
use Avronaut\Render\Json\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Json\Handler\JsonObjectNodeHandler
 * @uses   \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Node\JsonObjectNode
 * @uses   \Avronaut\Render\Json\HandlerAbstract
 * @uses   \Avronaut\Render\Json\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class JsonObjectNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithoutSiblings(): void
    {
        $node = new JsonObjectNode(new Position(0, 0));
        $writer = new BufferedWriter();
        $handler = new JsonObjectNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('{', $writer->getBuffer());
    }

    public function testVisitWithSiblings(): void
    {
        $node = new JsonObjectNode(new Position(0, 0));
        $node->addNode(new JsonObjectNode(new Position(0, 0)));
        $node->addNode(new JsonObjectNode(new Position(0, 0)));

        $writer = new BufferedWriter();
        $handler = new JsonObjectNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(1));

        $this->assertEquals(', {', $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new JsonObjectNode(new Position(0, 0));
        $writer = new BufferedWriter();
        $handler = new JsonObjectNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals('}', $writer->getBuffer());
    }
}
