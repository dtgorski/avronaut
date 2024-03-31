<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Diag;

use Avronaut\Avronaut;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Render\Diag\AstDumper;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Diag\AstDumper
 * @uses   \Avronaut\Avronaut
 * @uses   \Avronaut\AvroFileMap
 * @uses   \Avronaut\AvroFilePath
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\AvroProtocol
 * @uses   \Avronaut\AvroReference
 * @uses   \Avronaut\Load\AvdlFileLoader
 * @uses   \Avronaut\Node\DecimalTypeNode
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\EnumConstantNode
 * @uses   \Avronaut\Node\EnumDeclarationNode
 * @uses   \Avronaut\Node\ErrorListNode
 * @uses   \Avronaut\Node\FieldDeclarationNode
 * @uses   \Avronaut\Node\FixedDeclarationNode
 * @uses   \Avronaut\Node\JsonArrayNode
 * @uses   \Avronaut\Node\JsonFieldNode
 * @uses   \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Node\JsonObjectNode
 * @uses   \Avronaut\Node\JsonValueNode
 * @uses   \Avronaut\Node\LogicalTypeNode
 * @uses   \Avronaut\Node\MessageDeclarationNode
 * @uses   \Avronaut\Node\NamedDeclarationNode
 * @uses   \Avronaut\Node\PrimitiveTypeNode
 * @uses   \Avronaut\Node\ProtocolDeclarationNode
 * @uses   \Avronaut\Node\ReferenceTypeNode
 * @uses   \Avronaut\Node\ResultTypeNode
 * @uses   \Avronaut\Node\TypeNode
 * @uses   \Avronaut\Node\VariableDeclaratorNode
 * @uses   \Avronaut\Parse\AvdlParser
 * @uses   \Avronaut\Parse\ByteStreamReader
 * @uses   \Avronaut\Parse\CommentAwareCursor
 * @uses   \Avronaut\Parse\CommentSaveCursor
 * @uses   \Avronaut\Parse\CommentStack
 * @uses   \Avronaut\Parse\JsonParser
 * @uses   \Avronaut\Parse\Lexer
 * @uses   \Avronaut\Parse\ParserBase
 * @uses   \Avronaut\Parse\PropertiesWithNamespace
 * @uses   \Avronaut\Parse\Token
 * @uses   \Avronaut\Render\Walker
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comment
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\Property
 * @uses   \Avronaut\Tree\TreeNode
 * @uses   \Avronaut\Write\BufferedWriter
 */
class AstDumperTest extends AvroTestCase
{
    /** @dataProvider provideInputOutput */
    public function test(string $input, string $output): void
    {
        $avronaut = new Avronaut();

        $source = sprintf('%s/../../../data/%s', __DIR__, $input);
        $target = sprintf('%s/../../../data/%s', __DIR__, $output);

        $writer = new BufferedWriter();
        $proto = $avronaut->loadProtocol($source);
        $node = $proto->getFileMap()->getIterator()->current();

        $renderer = new AstDumper($writer);
        $node->accept($renderer);

        $expect = file_get_contents($target);

        $this->assertEquals($expect, $writer->getBuffer());
    }

    public static function provideInputOutput(): array
    {
        return [
            ["proto-01-in.avdl", "proto-01-out.dump"],
            ["proto-02-in.avdl", "proto-02-out.dump"],
        ];
    }
}
