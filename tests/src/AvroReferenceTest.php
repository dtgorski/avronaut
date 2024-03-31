<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests;

use Avronaut\AvroName;
use Avronaut\AvroNamespace;
use Avronaut\AvroReference;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Avronaut\AvroReference
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 */
class AvroReferenceTest extends TestCase
{
    public function testFromString(): void
    {
        $ref = AvroReference::fromString('bar');
        $this->assertEquals('', $ref->getNamespace()->getValue());
        $this->assertEquals('bar', $ref->getName()->getValue());

        $ref = AvroReference::fromString('foo.bar');
        $this->assertEquals('foo', $ref->getNamespace()->getValue());
        $this->assertEquals('bar', $ref->getName()->getValue());
    }

    public function testFromParts(): void
    {
        $namespace = AvroNamespace::fromString('foo');
        $name = AvroName::fromString('bar');

        $ref = AvroReference::fromQualified($namespace, $name);

        $this->assertSame($namespace, $ref->getNamespace());
        $this->assertSame($name, $ref->getName());
    }

    public function testToString(): void
    {
        $namespace = AvroNamespace::fromString('foo');
        $name = AvroName::fromString('bar');

        $ref = AvroReference::fromQualified($namespace, $name);
        $this->assertEquals('foo.bar', $ref->getQualifiedName());
    }
}
