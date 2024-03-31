<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Type;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Type\NamedType;

/**
 * @covers \Avronaut\Type\NamedType
 */
class NamedTypeTest extends AvroTestCase
{
    public function testNamedType(): void
    {
        $this->assertSame('ENUM', NamedType::ENUM->name);
        $this->assertSame('enum', NamedType::ENUM->value);

        $this->assertSame('ERROR', NamedType::ERROR->name);
        $this->assertSame('error', NamedType::ERROR->value);

        $this->assertSame('FIXED', NamedType::FIXED->name);
        $this->assertSame('fixed', NamedType::FIXED->value);

        $this->assertSame('RECORD', NamedType::RECORD->name);
        $this->assertSame('record', NamedType::RECORD->value);

        $this->assertEquals(NamedType::names(), NamedType::names());
    }

    public function testHasType(): void
    {
        $this->assertTrue(NamedType::hasType('enum'));
        $this->assertTrue(NamedType::hasType('error'));
        $this->assertTrue(NamedType::hasType('fixed'));
        $this->assertTrue(NamedType::hasType('record'));
    }
}
