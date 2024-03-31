<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\Node\JsonArrayNode;
use Avronaut\Node\JsonValueNode;
use Avronaut\Shared\Position;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Node\JsonArrayNode
 * @uses   \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Node\JsonValueNode
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class JsonArrayNodeTest extends AvroTestCase
{
    public function testJsonSerialize(): void
    {
        $type = new JsonArrayNode(new Position(0, 0));

        $type->addNode(new JsonValueNode(true, new Position(0, 0)));
        $type->addNode(new JsonValueNode(false, new Position(0, 0)));

        $this->assertSame('[true,false]', json_encode($type));
    }
}
