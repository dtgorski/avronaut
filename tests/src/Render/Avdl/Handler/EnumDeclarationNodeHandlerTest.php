<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\AvroName;
use Avronaut\Node\EnumDeclarationNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\EnumDeclarationNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\EnumDeclarationNodeHandler
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\EnumDeclarationNode
 * @uses   \Avronaut\Node\NamedDeclarationNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class EnumDeclarationNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new EnumDeclarationNode(AvroName::fromString('foo'), 'bar');
        $writer = new BufferedWriter();
        $handler = new EnumDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals("\nenum foo {\n", $writer->getBuffer());
    }

    public function testLeaveWithDefault(): void
    {
        $node = new EnumDeclarationNode(AvroName::fromString('foo'), 'bar');
        $writer = new BufferedWriter();
        $handler = new EnumDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);
        $handler->leave($node);

        $this->assertEquals("\nenum foo {\n} = bar;\n", $writer->getBuffer());
    }

    public function testLeaveWithoutDefault(): void
    {
        $node = new EnumDeclarationNode(AvroName::fromString('foo'), '');
        $writer = new BufferedWriter();
        $handler = new EnumDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);
        $handler->leave($node);

        $this->assertEquals("\nenum foo {\n}\n", $writer->getBuffer());
    }
}
