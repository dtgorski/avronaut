<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\Node\LogicalTypeNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Type\LogicalType;

/**
 * @covers \Avronaut\Node\LogicalTypeNode
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Type\LogicalType
 */
class LogicalTypeNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $type = new LogicalTypeNode(LogicalType::DATE);
        $this->assertSame('DATE', $type->getType()->name);
    }
}
