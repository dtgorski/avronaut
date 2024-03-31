<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Type;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Type\PrimitiveType;

/**
 * @covers \Avronaut\Type\PrimitiveType
 */
class PrimitiveTypeTest extends AvroTestCase
{
    public function testPrimitiveType(): void
    {
        $this->assertSame('BOOLEAN', PrimitiveType::BOOLEAN->name);
        $this->assertSame('boolean', PrimitiveType::BOOLEAN->value);

        $this->assertSame('BYTES', PrimitiveType::BYTES->name);
        $this->assertSame('bytes', PrimitiveType::BYTES->value);

        $this->assertSame('INT', PrimitiveType::INT->name);
        $this->assertSame('int', PrimitiveType::INT->value);

        $this->assertSame('STRING', PrimitiveType::STRING->name);
        $this->assertSame('string', PrimitiveType::STRING->value);

        $this->assertSame('FLOAT', PrimitiveType::FLOAT->name);
        $this->assertSame('float', PrimitiveType::FLOAT->value);

        $this->assertSame('DOUBLE', PrimitiveType::DOUBLE->name);
        $this->assertSame('double', PrimitiveType::DOUBLE->value);

        $this->assertSame('LONG', PrimitiveType::LONG->name);
        $this->assertSame('long', PrimitiveType::LONG->value);

        $this->assertSame('NULL', PrimitiveType::NULL->name);
        $this->assertSame('null', PrimitiveType::NULL->value);

        $this->assertEquals(PrimitiveType::names(), PrimitiveType::names());
    }

    public function testHasType(): void
    {
        $this->assertTrue(PrimitiveType::hasType('boolean'));
        $this->assertTrue(PrimitiveType::hasType('bytes'));
        $this->assertTrue(PrimitiveType::hasType('int'));
        $this->assertTrue(PrimitiveType::hasType('string'));
        $this->assertTrue(PrimitiveType::hasType('float'));
        $this->assertTrue(PrimitiveType::hasType('double'));
        $this->assertTrue(PrimitiveType::hasType('long'));
        $this->assertTrue(PrimitiveType::hasType('null'));
    }
}
