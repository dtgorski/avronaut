<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\AvroName;
use Avronaut\AvroNamespace;
use Avronaut\Node\NamedDeclarationNode;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Node\NamedDeclarationNode
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
class NamedDeclarationNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $name = AvroName::fromString('foo');
        $type = new NamedDeclarationNode($name);
        $this->assertSame($name, $type->getName());
    }


    public function testSetGetNamespace(): void
    {
        $name = AvroName::fromString('foo');
        $node = new class ($name) extends NamedDeclarationNode {
        };

        $this->assertSame('', $node->getNamespace()->getValue());

        $namespace = $this->createMock(AvroNamespace::class);
        $node->setNamespace($namespace);
        $this->assertSame($namespace, $node->getNamespace());
    }
}
