<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Json;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Tree\AstNode;
use Avronaut\Render\Json\HandlerAbstract;
use Avronaut\Render\Json\HandlerContext;
use Avronaut\Visitable;
use Avronaut\Write\BufferedWriter;
use Avronaut\Write\Writer;

/**
 * @covers \Avronaut\Render\Json\HandlerAbstract
 * @uses   \Avronaut\Render\Json\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 * @uses   \Avronaut\Write\Writer
 */
class HandlerAbstractTest extends AvroTestCase
{
    public function testVisitReturnsTrue(): void
    {
        $writer = $this->createMock(Writer::class);
        $ctx = new HandlerContext($writer);
        $handler = new BogusHandlerAbstract($ctx);

        $return = $handler->visit(new BogusAstNode());
        $this->assertTrue($return);
    }

    public function testLeave(): void
    {
        $writer = $this->createMock(Writer::class);
        $ctx = new HandlerContext($writer);
        $handler = new BogusHandlerAbstract($ctx);
        $handler->leave(new BogusAstNode());

        $this->assertTrue(true);
    }

    public function testWrite(): void
    {
        $writer = new BufferedWriter();
        $ctx = new HandlerContext($writer);
        $handler = new BogusHandlerAbstract($ctx);

        $handler->write('foo', 'bar');

        $this->assertEquals('foobar', $writer->getBuffer());
    }
}

// phpcs:disable
class BogusHandlerAbstract extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return true;
    }

    public function write(string|float|int|null ...$args): void
    {
        parent::write(...$args);
    }
}

class BogusAstNode extends AstNode
{
}
