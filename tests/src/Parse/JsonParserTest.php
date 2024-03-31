<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Parse;

use Avronaut\Parse\ByteStreamReader;
use Avronaut\Parse\CommentAwareCursor;
use Avronaut\Parse\JsonParser;
use Avronaut\Parse\Lexer;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Parse\JsonParser
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\Node\JsonFieldNode
 * @uses   \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Node\JsonObjectNode
 * @uses   \Avronaut\Node\JsonValueNode
 * @uses   \Avronaut\Parse\ByteStreamReader
 * @uses   \Avronaut\Parse\CommentAwareCursor
 * @uses   \Avronaut\Parse\JsonParser
 * @uses   \Avronaut\Parse\Lexer
 * @uses   \Avronaut\Parse\ParserBase
 * @uses   \Avronaut\Parse\Token
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\TreeNode
 */
class JsonParserTest extends AvroTestCase
{
    public function testThrowsExceptionOnUnexpectedToken(): void
    {
        $stream = $this->createStream('!');

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentAwareCursor((new Lexer())->createTokenStream($reader));

        $parser = new JsonParser($cursor);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/unexpected input/');

        $parser->parse();
    }

    public function testThrowsExceptionOnInvalidJson(): void
    {
        $stream = $this->createStream('invalid');

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentAwareCursor((new Lexer())->createTokenStream($reader));

        $parser = new JsonParser($cursor);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/valid JSON/');

        $parser->parse();
    }

    // Has not been covered by AvdlParserTest.
    public function testParseObjectWithMultipleFields(): void
    {
        $stream = $this->createStream('{"x": 1, "y": 2, "z": 3}');

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentAwareCursor((new Lexer())->createTokenStream($reader));

        $parser = new JsonParser($cursor);
        $node = $parser->parse();

        $this->assertEquals(3, $node->nodeCount());
    }
}
