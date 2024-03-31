<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\Node\ImportStatementNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Type\ImportType;

/**
 * @covers \Avronaut\Node\ImportStatementNode
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Type\ImportType
 */
class ImportStatementNodeTest extends AvroTestCase
{
    public function testGetType(): void
    {
        $type = new ImportStatementNode(ImportType::IDL, 'foo');
        $this->assertSame(ImportType::IDL, $type->getType());
    }

    public function testGetPath(): void
    {
        $type = new ImportStatementNode(ImportType::IDL, 'foo');
        $this->assertSame('foo', $type->getPath());
    }
}
