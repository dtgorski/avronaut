<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Tree;

use Avronaut\Tests\AvroTestCase;
use Avronaut\Tree\Comment;

/**
 * @covers \Avronaut\Tree\Comment
 * @uses   \Avronaut\Parse\Token
 */
class CommentTest extends AvroTestCase
{
    public function testFromStringSingleLineComment(): void
    {
        $str = '/** foo */';
        $com = Comment::fromString($str);

        $this->assertEquals('foo', $com->getValue());
    }

    public function testFromStringMultilineLineComment(): void
    {
        $str = "/**\n";
        $str .= "foo\n";
        $str .= "bar\n";
        $str .= "*/\n";
        $com = Comment::fromString($str);

        $this->assertEquals("foo\nbar", $com->getValue());
    }
}
