<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\AvroName;
use Avronaut\Node\RecordDeclarationNode;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Node\RecordDeclarationNode
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
class RecordDeclarationNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $name = AvroName::fromString('foo');
        $type = new RecordDeclarationNode($name);
        $this->assertSame($name, $type->getName());
    }
}
