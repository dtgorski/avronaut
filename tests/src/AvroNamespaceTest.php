<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests;

use Avronaut\AvroNamespace;

/**
 * @covers \Avronaut\AvroNamespace
 */
class AvroNamespaceTest extends AvroTestCase
{
    /** @dataProvider provideValidNamespaceNames */
    public function testValidNamespaces(string $namespace): void
    {
        try {
            AvroNamespace::fromString($namespace);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
        $this->assertTrue(true);
    }

    public static function provideValidNamespaceNames(): array
    {
        return [
            [''], ['_'], ['x'], ['x.y'], ['x.y.z'], ['x1.y.z'], ['x_'], ['_x'],
        ];
    }

    /** @dataProvider provideInvalidNamespaceNames */
    public function testInvalidNamespaces(string $namespace): void
    {
        $this->expectException(\Exception::class);
        AvroNamespace::fromString($namespace);
    }

    public static function provideInvalidNamespaceNames(): array
    {
        return [
            ['.'], ['.x'], ['x.'], ['1'], ['x.1'], ['x-y'], ['_x.']
        ];
    }

    public function testGetPath(): void
    {
        $this->assertEquals(['x'], AvroNamespace::fromString('x')->getPath());
        $this->assertEquals(['x', 'y'], AvroNamespace::fromString('x.y')->getPath());
    }

    public function testValue(): void
    {
        $ns = AvroNamespace::fromString('x');
        $this->assertEquals('x', $ns->getValue());
        $this->assertFalse($ns->isEmpty());
        $this->assertTrue(AvroNamespace::fromString('x')->equals($ns));
    }
}
