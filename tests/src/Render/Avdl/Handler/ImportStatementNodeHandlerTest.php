<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\AvroName;
use Avronaut\Node\ImportStatementNode;
use Avronaut\Node\ProtocolDeclarationNode;
use Avronaut\Node\TypeNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Type\ImportType;
use Avronaut\Render\Avdl\Handler\ImportStatementNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\ImportStatementNodeHandler
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\ImportStatementNode
 * @uses   \Avronaut\Node\NamedDeclarationNode
 * @uses   \Avronaut\Node\ProtocolDeclarationNode
 * @uses   \Avronaut\Node\TypeNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class ImportStatementNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithoutSiblingNodes(): void
    {
        $node = new ImportStatementNode(ImportType::IDL, 'foo');
        $writer = new BufferedWriter();
        $handler = new ImportStatementNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals("\nimport idl \"foo\";\n", $writer->getBuffer());

        $this->assertTrue($handler->visit(new TypeNode()));
    }

    public function testVisitWithSiblingNodes(): void
    {
        $node = new ProtocolDeclarationNode(AvroName::fromString('proto'));
        $node->addNode(new ImportStatementNode(ImportType::IDL, 'foo'));
        $node->addNode(new ImportStatementNode(ImportType::IDL, 'bar'));

        $writer = new BufferedWriter();
        $handler = new ImportStatementNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(0));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(1));

        $this->assertEquals("\nimport idl \"foo\";\nimport idl \"bar\";\n", $writer->getBuffer());
    }
}
