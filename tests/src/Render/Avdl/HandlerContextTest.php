<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\Writer;

/**
 * @covers \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Write\Writer
 */
class HandlerContextTest extends AvroTestCase
{
    public function testGetWriter(): void
    {
        $writer = $this->createMock(Writer::class);
        $ctx = new HandlerContext($writer);

        $this->assertSame($writer, $ctx->getWriter());
    }

    public function testManipulateDepth(): void
    {
        $writer = $this->createMock(Writer::class);
        $ctx = new HandlerContext($writer);

        $this->assertSame(0, $ctx->getDepth());

        $ctx->stepIn();
        $this->assertSame(1, $ctx->getDepth());

        $ctx->stepOut();
        $this->assertSame(0, $ctx->getDepth());

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('step underrun');

        $ctx->stepOut();
    }
}
