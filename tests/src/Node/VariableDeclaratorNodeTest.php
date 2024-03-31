<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\AvroName;
use Avronaut\Node\VariableDeclaratorNode;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Node\VariableDeclaratorNode
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class VariableDeclaratorNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $name = AvroName::fromString('foo');
        $type = new VariableDeclaratorNode($name);
        $this->assertSame($name, $type->getName());
    }
}
