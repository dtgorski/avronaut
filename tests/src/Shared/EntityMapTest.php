<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Shared;

use Avronaut\Shared\EntityMap;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Shared\EntityMap
 */
class EntityMapTest extends AvroTestCase
{
    public function test(): void
    {
        $map = BogusEntityMap::fromEmpty();
        $this->assertTrue($map->isEmpty());

        $array = [1, 2, 3];
        $map = BogusEntityMap::fromKeyValue($array);

        $this->assertEquals($array, $map->asArray());
        $this->assertEquals(3, $map->size());

        $it = $map->getIterator();
        $this->assertSame(1, $it->current());

        $it->next();
        $this->assertSame(2, $it->current());

        $this->assertTrue($map->has(1));
        $this->assertEquals(2, $map->get(1));

        $this->assertFalse($map->isEmpty());
        $this->assertFalse($map->has(3));
    }
}

/** @extends EntityMap<int, mixed> */
// phpcs:ignore
class BogusEntityMap extends EntityMap
{
}
