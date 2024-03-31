<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Json\Handler;

use Avronaut\Node\JsonArrayNode;
use Avronaut\Shared\Position;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Json\Handler\JsonArrayNodeHandler;
use Avronaut\Render\Json\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Json\Handler\JsonArrayNodeHandler
 * @uses   \Avronaut\Node\JsonArrayNode
 * @uses   \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Render\Json\HandlerAbstract
 * @uses   \Avronaut\Render\Json\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class JsonArrayNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithoutSiblings(): void
    {
        $node = new JsonArrayNode(new Position(0, 0));
        $writer = new BufferedWriter();
        $handler = new JsonArrayNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('[', $writer->getBuffer());
    }

    public function testVisitWithSiblings(): void
    {
        $node = new JsonArrayNode(new Position(0, 0));
        $node->addNode(new JsonArrayNode(new Position(0, 0)));
        $node->addNode(new JsonArrayNode(new Position(0, 0)));

        $writer = new BufferedWriter();
        $handler = new JsonArrayNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(1));

        $this->assertEquals(', [', $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new JsonArrayNode(new Position(0, 0));
        $writer = new BufferedWriter();
        $handler = new JsonArrayNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals(']', $writer->getBuffer());
    }
}
