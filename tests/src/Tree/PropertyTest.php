<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Tree;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Tree\Property;

/**
 * @covers \Avronaut\Tree\Property
 */
class PropertyTest extends AvroTestCase
{
    public function testGetNameGetValueString(): void
    {
        $prop = new Property('foo', 'bar');
        $this->assertEquals('foo', $prop->getName());
        $this->assertEquals('bar', $prop->getJson());
        $this->assertEquals('{"foo":"bar"}', json_encode($prop));
    }

    public function testGetNameGetValueBool(): void
    {
        $prop = new Property('foo', true);
        $this->assertEquals('foo', $prop->getName());
        $this->assertEquals(true, $prop->getJson());
        $this->assertEquals('{"foo":true}', json_encode($prop));
    }

    public function testGetNameGetValueNumber(): void
    {
        $prop = new Property('foo', 4.2);
        $this->assertEquals('foo', $prop->getName());
        $this->assertSame(4.2, $prop->getJson());
        $this->assertEquals('{"foo":4.2}', json_encode($prop));
    }

    public function testGetNameGetValueArray(): void
    {
        $prop = new Property('foo', [new Property('x', 'y')]);
        $this->assertEquals('foo', $prop->getName());
        $this->assertEquals([new Property('x', 'y')], $prop->getJson());
        $this->assertEquals('{"foo":[{"x":"y"}]}', json_encode($prop));
    }
}
