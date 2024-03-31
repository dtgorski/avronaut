<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\AvroName;
use Avronaut\Node\ErrorDeclarationNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Avdl\Handler\ErrorDeclarationNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\ErrorDeclarationNodeHandler
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\ErrorDeclarationNode
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
class ErrorDeclarationNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new ErrorDeclarationNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new ErrorDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals("\nerror foo {\n", $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new ErrorDeclarationNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new ErrorDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);
        $handler->leave($node);

        $this->assertEquals("\nerror foo {\n}\n", $writer->getBuffer());
    }
}
