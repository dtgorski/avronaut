<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Parse;

use Avronaut\Node\ImportStatementNode;
use Avronaut\Node\MessageDeclarationNode;
use Avronaut\Parse\AvdlParser;
use Avronaut\Parse\ByteStreamReader;
use Avronaut\Parse\CommentSaveCursor;
use Avronaut\Parse\Lexer;
use Avronaut\Parse\Token;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Parse\ParserBase
 * @covers \Avronaut\Parse\AvdlParser
 * @covers \Avronaut\Parse\JsonParser
 *
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\AvroReference
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\ImportStatementNode
 * @uses   \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Node\JsonValueNode
 * @uses   \Avronaut\Node\MessageDeclarationNode
 * @uses   \Avronaut\Node\NamedDeclarationNode
 * @uses   \Avronaut\Node\ProtocolDeclarationNode
 * @uses   \Avronaut\Node\ReferenceTypeNode
 * @uses   \Avronaut\Node\ResultTypeNode
 * @uses   \Avronaut\Node\TypeNode
 * @uses   \Avronaut\Parse\ByteStreamReader
 * @uses   \Avronaut\Parse\CommentAwareCursor
 * @uses   \Avronaut\Parse\CommentSaveCursor
 * @uses   \Avronaut\Parse\CommentStack
 * @uses   \Avronaut\Parse\Lexer
 * @uses   \Avronaut\Parse\PropertiesWithNamespace
 * @uses   \Avronaut\Parse\Token
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Property
 * @uses   \Avronaut\Tree\TreeNode
 */
class AvdlParserTest extends AvroTestCase
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    private Lexer $lexer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->lexer = new Lexer();
    }

    /** @param resource $stream */
    protected function createParser($stream): AvdlParser
    {
        $tokens = $this->lexer->createTokenStream(new ByteStreamReader($stream));
        return new AvdlParser(new CommentSaveCursor($tokens));
    }

    public function testImportStatement(): void
    {
        $stream = $this->createStream('protocol foo { import idl "bar"; }');
        $parser = $this->createParser($stream);

        $node = $parser->parse();

        /** @psalm-suppress PossiblyNullReference */
        $this->assertInstanceOf(ImportStatementNode::class, $node->nodeAt(0));
    }

    public function testMessageDeclaration(): void
    {
        $stream = $this->createStream('protocol `foo` { bar.baz.A B(); }');
        $parser = $this->createParser($stream);

        $node = $parser->parse();

        /** @psalm-suppress PossiblyNullReference */
        $this->assertInstanceOf(MessageDeclarationNode::class, $node->nodeAt(0));
    }

    public function testExpectEOF(): void
    {
        $stream = $this->createStream('');
        $parser = $this->createParser($stream);

        $this->assertFalse($parser->expect(Token::TICK));
    }

    public function testThrowsExceptionWhenConsumeEOF(): void
    {
        $stream = $this->createStream('');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->assertFalse($parser->consume(Token::TICK));
    }

    public function testThrowsExceptionWhenConsumeWithHintEOF(): void
    {
        $stream = $this->createStream('');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->assertFalse($parser->consumeWithHint(Token::TICK, ''));
    }

    public function testThrowsExceptionWhenConsumeWrongTokenType(): void
    {
        $stream = $this->createStream('foo');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->assertFalse($parser->consume(Token::TICK));
    }

    public function testThrowsExceptionWhenConsumeWithHintWrongTokenType(): void
    {
        $stream = $this->createStream('foo');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->assertFalse($parser->consumeWithHint(Token::TICK, ''));
    }

    public function testThrowsExceptionWhenConsumeUnacceptedTokenLoad(): void
    {
        $stream = $this->createStream('foo');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->assertFalse($parser->consume(Token::IDENT, 'bar'));
    }

    public function testThrowsExceptionWhenConsumeWithHintUnacceptedTokenLoad(): void
    {
        $stream = $this->createStream('foo');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->assertFalse($parser->consumeWithHint(Token::IDENT, '', 'bar'));
    }

    public function testThrowsExceptionWhenInvalidDecimalTypePrecision(): void
    {
        $stream = $this->createStream('protocol x { decimal(-1, 0) foo(); } ');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/unexpected negative decimal type precision/');
        $parser->parse();
    }

    public function testThrowsExceptionWhenInvalidDecimalTypeScale(): void
    {
        $stream = $this->createStream('protocol x { decimal(0, -1) foo(); } ');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/unexpected invalid decimal type scale/');
        $parser->parse();
    }

    public function testThrowsExceptionWhenInvalidNamespace(): void
    {
        $stream = $this->createStream('@namespace(null)');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/value to be string/');
        $parser->parse();
    }
}
