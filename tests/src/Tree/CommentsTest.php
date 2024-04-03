<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Tree;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Tree\Comment;
use Avronaut\Tree\Comments;

/**
 * @covers \Avronaut\Tree\Comments
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Tree\Comment
 */
class CommentsTest extends AvroTestCase
{
    public function testComments(): void
    {
        $comment1 = Comment::fromString('foo');
        $comment2 = Comment::fromString('bar');

        $comments = Comments::fromArray([$comment1, $comment2]);

        $this->assertSame(2, $comments->size());
        $this->assertEquals([$comment1, $comment2], $comments->asArray());
        $this->assertSame($comment1, $comments->getIterator()->current());
    }
}
