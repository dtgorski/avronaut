<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Tree\AstNode;
use Avronaut\Render\HandlerDelegate;
use Avronaut\Render\NodeHandler;

/**
 * @covers \Avronaut\Render\HandlerDelegate
 * @uses   \Avronaut\Render\NodeHandler
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class HandlerDelegateTest extends AvroTestCase
{
    public function testAddHandler(): void
    {
        $handler = $this->createMock(NodeHandler::class);
        $delegate = new HandlerDelegate();
        $delegate->addHandler($handler);
        $this->assertSame($handler, $delegate->getHandlers()[0]);
    }

    public function testVisit(): void
    {
        $node = new DelegateTestNode();

        $handlerA = $this->createMock(NodeHandler::class);
        $handlerA->method('canHandle')->willReturn(false);
        $handlerA->expects($this->never())->method('visit');

        $handlerB = $this->createMock(NodeHandler::class);
        $handlerB->method('canHandle')->willReturn(true);
        $handlerB->expects($this->once())->method('visit')->with($node)->willReturn(true);

        $delegate = new HandlerDelegate([
            $handlerA,
            $handlerB,
        ]);

        $this->assertTrue($delegate->visit($node));
    }

    public function testVisitReturnsTrue(): void
    {
        $node = new DelegateTestNode();
        $delegate = new HandlerDelegate([]);

        $this->assertTrue($delegate->visit($node));
    }

    public function testLeave(): void
    {
        $node = new DelegateTestNode();

        $handlerA = $this->createMock(NodeHandler::class);
        $handlerA->method('canHandle')->willReturn(false);
        $handlerA->expects($this->never())->method('leave');

        $handlerB = $this->createMock(NodeHandler::class);
        $handlerB->method('canHandle')->willReturn(true);
        $handlerB->expects($this->once())->method('leave')->with($node);

        $delegate = new HandlerDelegate([
            $handlerA,
            $handlerB,
        ]);

        $delegate->leave($node);
    }

    public function testGetHandlers(): void
    {
        $handlers = [];
        $delegate = new HandlerDelegate($handlers);

        $this->assertSame($handlers, $delegate->getHandlers());
    }
}

// phpcs:ignore
class DelegateTestNode extends AstNode
{
}
