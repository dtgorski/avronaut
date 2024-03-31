<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\Node\ErrorListNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Type\ErrorType;

/**
 * @covers \Avronaut\Node\ErrorListNode
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Type\ErrorType
 */
class ErrorListNodeTest extends AvroTestCase
{
    public function testGetType(): void
    {
        $type = new ErrorListNode(ErrorType::ONEWAY);
        $this->assertEquals($type->getType()->name, ErrorType::ONEWAY->name);
    }
}
