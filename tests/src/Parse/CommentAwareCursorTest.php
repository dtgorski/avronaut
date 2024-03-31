<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Parse;

use Avronaut\Parse\ByteStreamReader;
use Avronaut\Parse\CommentAwareCursor;
use Avronaut\Parse\CommentStack;
use Avronaut\Parse\Lexer;
use Avronaut\Parse\Token;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Parse\CommentAwareCursor
 * @uses   \Avronaut\Parse\ByteStreamReader
 * @uses   \Avronaut\Parse\CommentStack
 * @uses   \Avronaut\Parse\Lexer
 * @uses   \Avronaut\Parse\Token
 * @uses   \Avronaut\Shared\Position
 */
class CommentAwareCursorTest extends AvroTestCase
{
    public function testCursorProvidesTokens(): void
    {
        $stream = $this->createStream('foo . bar : record /**/ //');

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentAwareCursor((new Lexer())->createTokenStream($reader));

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

        $this->assertEquals(Token::COMBLCK, $cursor->peek()->getType());
        $cursor->next();

        $this->assertEquals(Token::COMLINE, $cursor->peek()->getType());
        $cursor->next();

        $this->assertEquals(Token::EOF, $cursor->peek()->getType());
        $cursor->next();

        $this->closeStream($stream);
    }

    public function testCursorProvidesEOF(): void
    {
        $stream = $this->createStream('');

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentAwareCursor((new Lexer())->createTokenStream($reader));

        $this->assertEquals(Token::EOF, $cursor->next()->getType());

        $this->closeStream($stream);
    }

    public function testGetCommentStack(): void
    {
        $stream = $this->createStream('');

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentAwareCursor(
            (new Lexer())->createTokenStream($reader),
            $comments = new CommentStack()
        );

        $this->assertSame($comments, $cursor->getCommentStack());
    }
}
