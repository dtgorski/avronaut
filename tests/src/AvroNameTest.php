<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests;

use Avronaut\AvroName;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Avronaut\AvroName
 */
class AvroNameTest extends TestCase
{
    /** @dataProvider provideValidNames */
    public function testValidNames(string $name): void
    {
        try {
            AvroName::fromString($name);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
        $this->assertTrue(true);
    }

    public static function provideValidNames(): array
    {
        return [['x'], ['xy'], ['_'], ['$']];
    }

    /** @dataProvider provideInvalidNames */
    public function testInvalidNames(string $name): void
    {
        $this->expectException(\Exception::class);
        AvroName::fromString($name);
    }

    public static function provideInvalidNames(): array
    {
        return [[''], ['.'], ['.x'], ['x.'], ['1'], ['x.1'], ['x-y'], ['_x.']];
    }

    public function testGetValue(): void
    {
        $this->assertEquals('x', AvroName::fromString('x')->getValue());
    }

    public function testEquals(): void
    {
        $n1 = AvroName::fromString('foo');
        $n2 = AvroName::fromString('foo');
        $n3 = AvroName::fromString('bar');

        $this->assertTrue($n1->equals($n2));
        $this->assertFalse($n1->equals($n3));
    }
}
