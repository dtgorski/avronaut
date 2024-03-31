<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Shared;

use Avronaut\Shared\Position;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Shared\Position
 */
class PositionTest extends AvroTestCase
{
    public function test(): void
    {
        $pos = new Position(1, 2);
        $this->assertSame(1, $pos->getLine());
        $this->assertSame(2, $pos->getColumn());
    }
}
