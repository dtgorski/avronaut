<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Type;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Type\LogicalType;

/**
 * @covers \Avronaut\Type\LogicalType
 */
class LogicalTypeTest extends AvroTestCase
{
    public function testLogicalType(): void
    {
        $this->assertSame('DATE', LogicalType::DATE->name);
        $this->assertSame('date', LogicalType::DATE->value);

        $this->assertSame('TIME_MS', LogicalType::TIME_MS->name);
        $this->assertSame('time_ms', LogicalType::TIME_MS->value);

        $this->assertSame('TIMESTAMP_MS', LogicalType::TIMESTAMP_MS->name);
        $this->assertSame('timestamp_ms', LogicalType::TIMESTAMP_MS->value);

        $this->assertSame('LOCAL_TIMESTAMP_MS', LogicalType::LOCAL_TIMESTAMP_MS->name);
        $this->assertSame('local_timestamp_ms', LogicalType::LOCAL_TIMESTAMP_MS->value);

        $this->assertSame('UUID', LogicalType::UUID->name);
        $this->assertSame('uuid', LogicalType::UUID->value);

        $this->assertEquals(LogicalType::names(), LogicalType::names());
    }

    public function testHasType(): void
    {
        $this->assertTrue(LogicalType::hasType('date'));
        $this->assertTrue(LogicalType::hasType('time_ms'));
        $this->assertTrue(LogicalType::hasType('timestamp_ms'));
        $this->assertTrue(LogicalType::hasType('local_timestamp_ms'));
        $this->assertTrue(LogicalType::hasType('uuid'));
    }
}
