<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\AvroName;
use Avronaut\Node\FixedDeclarationNode;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Node\FixedDeclarationNode
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\NamedDeclarationNode
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class FixedDeclarationNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $name = AvroName::fromString('foo');
        $type = new FixedDeclarationNode($name, 42);
        $this->assertSame($name, $type->getName());
    }

    public function testGetValue(): void
    {
        $type = new FixedDeclarationNode(AvroName::fromString('foo'), 42);
        $this->assertSame(42, $type->getValue());
    }
}
