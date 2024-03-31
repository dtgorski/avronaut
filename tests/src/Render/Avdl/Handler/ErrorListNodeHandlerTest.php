<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl\Handler;

use Avronaut\Node\ErrorListNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Type\ErrorType;
use Avronaut\Render\Avdl\Handler\ErrorListNodeHandler;
use Avronaut\Render\Avdl\HandlerContext;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\Handler\ErrorListNodeHandler
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\ErrorDeclarationNode
 * @uses   \Avronaut\Node\ErrorListNode
 * @uses   \Avronaut\Render\Avdl\HandlerAbstract
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Type\ErrorType
 * @uses   \Avronaut\Write\BufferedWriter
 */
class ErrorListNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new ErrorListNode(ErrorType::THROWS);
        $writer = new BufferedWriter();
        $handler = new ErrorListNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals(' throws ', $writer->getBuffer());
    }
}
