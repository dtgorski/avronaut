<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\Node\JsonNode;
use Avronaut\Shared\Position;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class JsonNodeTest extends AvroTestCase
{
    public function test(): void
    {
        $pos = new Position(0, 0);
        $node = new class ($pos) extends JsonNode {
        };
        $this->assertSame($pos, $node->getPosition());
    }
}
