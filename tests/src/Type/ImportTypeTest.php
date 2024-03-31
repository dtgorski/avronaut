<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Type;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Type\ImportType;

/**
 * @covers \Avronaut\Type\ImportType
 */
class ImportTypeTest extends AvroTestCase
{
    public function testImportType(): void
    {
        $this->assertSame('IDL', ImportType::IDL->name);
        $this->assertSame('idl', ImportType::IDL->value);

        $this->assertSame('PROTOCOL', ImportType::PROTOCOL->name);
        $this->assertSame('protocol', ImportType::PROTOCOL->value);

        $this->assertSame('SCHEMA', ImportType::SCHEMA->name);
        $this->assertSame('schema', ImportType::SCHEMA->value);
    }

    public function testHasType(): void
    {
        $this->assertTrue(ImportType::hasType('idl'));
        $this->assertTrue(ImportType::hasType('protocol'));
        $this->assertTrue(ImportType::hasType('schema'));
    }
}
