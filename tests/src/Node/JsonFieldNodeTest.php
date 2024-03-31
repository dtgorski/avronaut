<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\AvroName;
use Avronaut\Node\JsonFieldNode;
use Avronaut\Shared\Position;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Node\JsonFieldNode
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class JsonFieldNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $name = AvroName::fromString('foo');
        $type = new JsonFieldNode($name, new Position(0, 0));
        $this->assertSame($name, $type->getName());
    }

    public function testJsonSerialize(): void
    {
        $type = new JsonFieldNode(AvroName::fromString('foo'), new Position(0, 0));
        $this->assertSame('"foo"', json_encode($type));
    }
}
