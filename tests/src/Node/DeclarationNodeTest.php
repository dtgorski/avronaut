<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Node;

use Avronaut\Node\DeclarationNode;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Tree\Comment;
use Avronaut\Tree\Comments;

/**
 * @covers \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\AvroFilePath
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comment
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class DeclarationNodeTest extends AvroTestCase
{
    public function testAddGetComments(): void
    {
        $node = new class extends DeclarationNode {
        };

        $this->assertSame(0, $node->getComments()->size());
        $node->setComments(Comments::fromKeyValue([Comment::fromString('foo'), Comment::fromString('bar')]));
        $this->assertSame(2, $node->getComments()->size());

        $test = function (Comment $comment, int $i): void {
            $expect = ['foo', 'bar'];
            $this->assertEquals($expect[$i], $comment->getValue());
        };

        $i = 0;
        foreach ($node->getComments() as $comment) {
            $test($comment, $i++);
        }
    }
}
