<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Parse;

use Avronaut\Parse\ByteStreamReader;
use Avronaut\Parse\CommentSaveCursor;
use Avronaut\Parse\CommentStack;
use Avronaut\Parse\Lexer;
use Avronaut\Parse\Token;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Parse\CommentSaveCursor
 * @uses   \Avronaut\Parse\ByteStreamReader
 * @uses   \Avronaut\Parse\CommentAwareCursor
 * @uses   \Avronaut\Parse\CommentStack
 * @uses   \Avronaut\Parse\Lexer
 * @uses   \Avronaut\Parse\Token
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\Comment
 */
class CommentSaveCursorTest extends AvroTestCase
{
    public function testCursorPeek(): void
    {
        $stream = $this->createStream('/**/ foo . bar : record /**/ //');

        $reader = new ByteStreamReader($stream);
        $stack = new CommentStack();

        $cursor = new CommentSaveCursor(
            (new Lexer())->createTokenStream($reader),
            $stack
        );

        $this->assertEquals(Token::IDENT, $cursor->peek()->getType());
        $this->assertEquals('foo', $cursor->peek()->getLoad());
        $cursor->next();

        $this->assertEquals(Token::DOT, $cursor->peek()->getType());
        $cursor->next();

        $this->assertEquals(Token::IDENT, $cursor->peek()->getType());
        $this->assertEquals('bar', $cursor->peek()->getLoad());
        $cursor->next();

        $this->assertEquals(Token::COLON, $cursor->peek()->getType());
        $cursor->next();

        $this->assertEquals(Token::IDENT, $cursor->peek()->getType());
        $this->assertEquals('record', $cursor->peek()->getLoad());
        $cursor->next();

        $this->assertEquals(Token::EOF, $cursor->peek()->getType());
        $cursor->next();

        $this->closeStream($stream);

        $this->assertEquals(2, $stack->size());
    }

    public function testCursorNext(): void
    {
        $stream = $this->createStream('/**/ /**/ //');

        $reader = new ByteStreamReader($stream);
        $stack = new CommentStack();

        $cursor = new CommentSaveCursor(
            (new Lexer())->createTokenStream($reader),
            $stack
        );

        $this->assertEquals(Token::EOF, $cursor->next()->getType());

        $this->assertEquals(2, $stack->size());
    }
}
