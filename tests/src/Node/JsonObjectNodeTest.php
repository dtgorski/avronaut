<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\AvroName;
use Avronaut\Node\JsonFieldNode;
use Avronaut\Node\JsonObjectNode;
use Avronaut\Node\JsonValueNode;
use Avronaut\Shared\Position;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Node\JsonObjectNode
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\Node\JsonFieldNode
 * @uses   \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Node\JsonValueNode
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class JsonObjectNodeTest extends AvroTestCase
{
    public function testJsonSerialize(): void
    {
        $type = new JsonObjectNode(new Position(0, 0));

        $node1 = new JsonFieldNode(AvroName::fromString('foo'), new Position(0, 0));
        $node2 = new JsonValueNode(true, new Position(0, 0));
        $type->addNode(($node1)->addNode($node2));

        $node1 = new JsonFieldNode(AvroName::fromString('bar'), new Position(0, 0));
        $node2 = new JsonValueNode(false, new Position(0, 0));
        $type->addNode($node1->addNode($node2));

        $this->assertSame('{"foo":true,"bar":false}', json_encode($type));
    }
}
