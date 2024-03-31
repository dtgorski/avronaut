<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\Node\JsonValueNode;
use Avronaut\Shared\Position;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Node\JsonValueNode
 * @uses   \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class JsonValueNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $type = new JsonValueNode('foo', new Position(0, 0));
        $this->assertSame('foo', $type->getValue());
    }

    public function testJsonSerialize(): void
    {
        $type = new JsonValueNode('foo', new Position(0, 0));
        $this->assertSame('"foo"', json_encode($type));
    }
}
