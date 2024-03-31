<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Json;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Json\HandlerContext;
use Avronaut\Write\Writer;

/**
 * @covers \Avronaut\Render\Json\HandlerContext
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
}
