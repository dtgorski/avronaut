<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Parse;

use Avronaut\Parse\ByteStreamReader;
use Avronaut\Parse\CommentAwareCursor;
use Avronaut\Parse\Lexer;
use Avronaut\Parse\Token;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Parse\ByteStreamReader
 * @uses   \Avronaut\Parse\CommentAwareCursor
 * @uses   \Avronaut\Parse\CommentStack
 * @uses   \Avronaut\Parse\Lexer
 * @uses   \Avronaut\Parse\Token
 * @uses   \Avronaut\Shared\Position
 */
class ByteStreamReaderTest extends AvroTestCase
{
    public function testInvalidInitialization(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @psalm-suppress NullArgument */
        new ByteStreamReader(null);
    }

    public function testLineAndColumnAndLoadReportedOnEmptyDocument(): void
    {
        $stream = $this->createStream('');

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentAwareCursor((new Lexer())->createTokenStream($reader));

        $this->assertSame(1, $cursor->peek()->getPosition()->getLine());
        $this->assertSame(0, $cursor->peek()->getPosition()->getColumn());
        $this->assertTrue($cursor->peek()->is(Token::EOF));

        $this->closeStream($stream);
    }

    public function testLineAndColumnAndLoadReported(): void
    {
        $stream = $this->createStream('');
        fwrite($stream, "foo .\n");        // line #1
        fwrite($stream, "bar :\n");        // line #2
        fwrite($stream, "\n");             // line #3
        fwrite($stream, "record\t/**/\n"); // line #4
        fwrite($stream, " //");            // line #5
        fseek($stream, 0);

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentAwareCursor((new Lexer())->createTokenStream($reader));

        /** @var array<int, array<int, array>> $expect */
        $expect = [
            [1, 1], [1, 5], // line #1
            [2, 1], [2, 5], // line #2
            [4, 1], [4, 9], // line #4
            [5, 2]          // line #5
        ];

        $i = 0;
        while (($token = $cursor->next())->isNot(Token::EOF)) {
            $line = $expect[$i][0];
            $col = $expect[$i][1];
            $i++;

            $position = $token->getPosition();
            $this->assertEquals($line, $position->getLine(), sprintf('Line fail for %s:', $token->getLoad()));
            $this->assertEquals($col, $position->getColumn(), sprintf('Column fail for %s:', $token->getLoad()));
        }
        fclose($stream);
    }
}
