<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 03/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\Node\TypeNode;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Avronaut\Node\TypeNode
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\TreeNode
 */
class TypeNodeTest extends TestCase
{
    public function testNullable(): void
    {
        $node = new TypeNode();
        $this->assertFalse($node->isNullable());

        $node = new TypeNode(true);
        $this->assertTrue($node->isNullable());
    }
}
