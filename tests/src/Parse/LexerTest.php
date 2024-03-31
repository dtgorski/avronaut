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
 * @covers \Avronaut\Parse\Lexer
 * @uses   \Avronaut\Parse\ByteStreamReader
 * @uses   \Avronaut\Parse\CommentAwareCursor
 * @uses   \Avronaut\Parse\CommentStack
 * @uses   \Avronaut\Parse\Token
 * @uses   \Avronaut\Shared\Position
 */
class LexerTest extends AvroTestCase
{
    /** @dataProvider provideValidNumbers */
    public function testCanHandleValidNumbers(string $src, array $loads): void
    {
        $stream = $this->createStream($src);
        $cursor = $this->createCursor($stream);

        $token = $cursor->next();
        $this->assertTrue($token->is(Token::NUMBER));
        $this->assertTrue($cursor->peek()->is(Token::EOF));
        $this->assertSame($loads[0], (float)$token->getLoad());

        $this->closeStream($stream);
    }

    /** @param resource $stream */
    protected function createCursor($stream): CommentAwareCursor
    {
        $reader = new ByteStreamReader($stream);
        return new CommentAwareCursor((new Lexer())->createTokenStream($reader));
    }

    public static function provideValidNumbers(): array
    {
        // @formatter:off
        return [
            ['00',      [0.0]],
            ['+0',      [0.0]],
            ['-0',      [(float)-0]],
            ['.0',      [0.0]],
            ['0.',      [0.0]],
            ['-.0',     [-.0]],
            ['-0.0',    [-0.0]],
            ['1',       [1.0]],
            ['+1',      [1.0]],
            ['+1.',     [1.0]],
            ['-1',      [-1.0]],
            ['+1.1',    [1.1]],
            ['-1.1',    [-1.1]],
            ['.1',      [.1]],
            ['+.1',     [+.1]],
            ['-.1',     [-.1]],
            ['+12E1',   [+12E1]],
            ['-1.2e1',  [-1.2e1]],
            ['-1.2e-0', [-1.2e-0]],
            ['1.2e+12', [1.2e+12]],
            ['+.0',     [+.0]],
            ['.0e-1',   [.0e-1]],
            ['-.0e1',   [-.0e1]],
            ['-.0e+1',  [-.0e+1]],
            ['-.0e12',  [(float)-0]],
            ['.0e-12',  [0.0]],
            ['-.01e2',  [-1.0]],
            ['-.1e02',  [-10.0]],
            ['-0.1e2',  [-10.0]],
            ['-.2e01',  [-2.0]],
            ['-.2e10',  [-2000000000.0]],
            ['-.10e2',  [-10.0]],
            ['-1.0e2',  [-100.0]],
            ['-1.2e0',  [-1.2]],
            ['-2.0e1',  [-20.0]],
            ['.01e-2',  [0.0001]],
            ['.1e-02',  [0.001]],
            ['0.1e-2',  [0.001]],
            ['.02e-1',  [0.002]],
            ['.2e-01',  [0.02]],
            ['0.2e-1',  [0.02]],
            ['.20e-1',  [0.02]],
            ['1.0e-2',  [0.01]],
            ['2.1e-0',  [2.1]],
        ];
        // @formatter:on
    }

    /** @dataProvider provideInvalidNumbers */
    public function testCanHandleInvalidNumbers(string $src, array $loads): void
    {
        $stream = $this->createStream($src);
        $cursor = $this->createCursor($stream);

        $token = $cursor->peek();
        $this->assertTrue($token->is(Token::ERR));
        $this->assertSame($loads[0], $token->getLoad());

        $this->closeStream($stream);
    }

    public static function provideInvalidNumbers(): array
    {
        // @formatter:off
        return [
            ['-01e.2', ['unexpected \'.\' (0x2E)']],
            ['-1e.02', ['unexpected \'.\' (0x2E)']],
            ['-1e.20', ['unexpected \'.\' (0x2E)']],
            ['-02e.1', ['unexpected \'.\' (0x2E)']],
            ['-2e.01', ['unexpected \'.\' (0x2E)']],
            ['-10e.2', ['unexpected \'.\' (0x2E)']],
            ['-0e.12', ['unexpected \'.\' (0x2E)']],
            ['01e-.2', ['unexpected \'.\' (0x2E)']],
            ['1e-.02', ['unexpected \'.\' (0x2E)']],
            ['1e-.20', ['unexpected \'.\' (0x2E)']],
            ['02e-.1', ['unexpected \'.\' (0x2E)']],
            ['2e-.01', ['unexpected \'.\' (0x2E)']],
            ['2e-.10', ['unexpected \'.\' (0x2E)']],
            ['12e-.0', ['unexpected \'.\' (0x2E)']],
            ['0e-.12', ['unexpected \'.\' (0x2E)']],
        ];
        // @formatter:on
    }

    /** @dataProvider provideValidSequences */
    public function testCanHandleValidSequences(string $src, int $type): void
    {
        $stream = $this->createStream($src);
        $cursor = $this->createCursor($stream);

        $this->assertSame($type, $cursor->peek()->getType());

        $type === Token::STRING
            ? $this->assertSame($src, '"' . $cursor->peek()->getLoad() . '"')
            : $this->assertSame($src, $cursor->peek()->getLoad());

        $this->closeStream($stream);
    }

    public static function provideValidSequences(): array
    {
        // @formatter:off
        return [
            ['',           Token::EOF    ],
            ['@',          Token::AT     ],
            ['<',          Token::LT     ],
            ['>',          Token::GT     ],
            ['.',          Token::DOT    ],
            [',',          Token::COMMA  ],
            [':',          Token::COLON  ],
            [';',          Token::SEMICOL],
            ['=',          Token::EQ     ],
            ['?',          Token::QMARK  ],
            ['/',          Token::SLASH  ],
            ['//',         Token::COMLINE],
            ['/*',         Token::COMBLCK],
            ['[',          Token::LBRACK ],
            [']',          Token::RBRACK ],
            ['{',          Token::LBRACE ],
            ['}',          Token::RBRACE ],
            ['(',          Token::LPAREN ],
            [')',          Token::RPAREN ],
            ['"string"',   Token::STRING ],
            ['identifier', Token::IDENT  ],
            ['00',         Token::NUMBER ],
            ['+0',         Token::NUMBER ],
            ['-0',         Token::NUMBER ],
            ['.0',         Token::NUMBER ],
            ['0.',         Token::NUMBER ],
            ['-.0',        Token::NUMBER ],
            ['-0.0',       Token::NUMBER ],
            ['1',          Token::NUMBER ],
            ['+1',         Token::NUMBER ],
            ['-1',         Token::NUMBER ],
            ['+1.1',       Token::NUMBER ],
            ['-1.1',       Token::NUMBER ],
            ['.1',         Token::NUMBER ],
            ['+.1',        Token::NUMBER ],
            ['-.1',        Token::NUMBER ],
            ['+12E1',      Token::NUMBER ],
            ['-1.2e1',     Token::NUMBER ],
            ['-1.2e-0',    Token::NUMBER ],
            ['-1.2e+12',   Token::NUMBER ],
            ['+.0',        Token::NUMBER ],
            ['.0e-1',      Token::NUMBER ],
            ['-.0e1',      Token::NUMBER ],
            ['-.0e+1',     Token::NUMBER ],
        ];
        // @formatter:on
    }

    /** @dataProvider provideMangledSequences */
    public function testCanHandleMangledSequences(string $src, array $loads): void
    {
        $stream = $this->createStream($src);
        $cursor = $this->createCursor($stream);

        $i = 0;
        while (($token = $cursor->next())->isNot(Token::EOF)) {
            $this->assertSame(
                $loads[$i++],
                $token->getLoad(),
                $token->getSymbol()
            );
        }
        $this->assertSame(sizeof($loads), $i, 'Token count mismatch:');

        $this->closeStream($stream);
    }

    public static function provideMangledSequences(): array
    {
        // @formatter:off
        return [

            // got something unexpected…
            ['~',       ['unexpected \'~\' (0x7E)']],
            ['-.x',     ['unexpected \'x\' (0x78)']],
            ['-.e',     ['unexpected \'e\' (0x65)']],
            ['1ee',     ['unexpected \'e\' (0x65)']],
            ['1e+-',    ['unexpected \'-\' (0x2D)']],

            // expected something, but got nothing…
            ['-.',      ['unexpected end of input']],
            ['+1e',     ['unexpected end of input']],
            ['1e-',     ['unexpected end of input']],
            ['1e',      ['unexpected end of input']],
            ['1e.',     ['unexpected \'.\' (0x2E)']],
            ['1e+',     ['unexpected end of input']],
            ['1e+.',    ['unexpected \'.\' (0x2E)']],

            // miscellaneous…
            ['1e-0',    ['1e-0']],
            ['1e+0',    ['1e+0']],
            ['1e0-',    ['1e0', '-']],
            ['1e0+',    ['1e0', 'unexpected end of input']],
            ['1e0-1',   ['1e0', '-1']],
            ['1e0+1',   ['1e0', '+1']],
            ['/--//',   ['/', '-', '-', '//']],
            ['.x',      ['.', 'x']],
            ['-1x',     ['-1', 'x']],
            ['1-',      ['1', '-']],
            ['-1',      ['-1']],
            ['-1.',     ['-1.']],
        ];
        // @formatter:on
    }

    public function testLineAndColumnAndLoadReported(): void
    {
        $stream = $this->openStream(sprintf('%s/../../data/proto-01-in.avdl', __DIR__));
        $expect = $this->openStream(sprintf('%s/../../data/proto-01-load.csv', __DIR__));

        $cursor = $this->createCursor($stream);

        while (($token = $cursor->next())->isNot(Token::EOF)) {

            /** @psalm-suppress PossiblyUndefinedArrayOffset */
            list($wantLine, $wantCol, $wantLoad) = (array) fgetcsv($expect);

            $position = $token->getPosition();
            $this->assertEquals($wantLine, $position->getLine(), sprintf('Line fail for \'%s\':', $wantLoad));
            $this->assertEquals($wantCol, $position->getColumn(), sprintf('Col fail for \'%s\':', $wantLoad));
            $this->assertEquals($wantLoad, $token->getLoad(), sprintf('Load fail for \'%s\':', $wantLoad));
        }

        $this->closeStream($expect);
        $this->closeStream($stream);
    }
}
