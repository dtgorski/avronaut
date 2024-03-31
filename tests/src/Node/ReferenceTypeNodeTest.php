<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\AvroReference;
use Avronaut\Node\ReferenceTypeNode;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Node\ReferenceTypeNode
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\AvroReference
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class ReferenceTypeNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $ref = AvroReference::fromString('foo.bar');
        $type = new ReferenceTypeNode($ref);
        $this->assertSame($ref, $type->getReference());
    }
}
