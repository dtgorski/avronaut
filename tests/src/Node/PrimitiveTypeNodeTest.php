<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\Node\PrimitiveTypeNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Type\PrimitiveType;

/**
 * @covers \Avronaut\Node\PrimitiveTypeNode
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class PrimitiveTypeNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $type = new PrimitiveTypeNode(PrimitiveType::INT);
        $this->assertSame('INT', $type->getType()->name);
        $this->assertSame('int', $type->getType()->value);
    }
}
