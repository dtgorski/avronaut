<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Write;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Write\StandardWriter;

/**
 * @covers \Avronaut\Write\StandardWriter
 */
class StandardWriterTest extends AvroTestCase
{
    public function testWriteString(): void
    {
        $writer = new StandardWriter();

        ob_start();
        $writer->write("foo\n");
        $this->assertEquals("foo\n", ob_get_clean());
    }
}
