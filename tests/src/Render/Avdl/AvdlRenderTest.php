<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Render\Avdl;

use Avronaut\Avronaut;
use Avronaut\Tests\AvroTestCase;
use Avronaut\Visitor;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\Render\Avdl\HandlerAbstract
 * @covers \Avronaut\Parse\AvdlParser
 * @covers \Avronaut\Parse\JsonParser
 * @covers \Avronaut\Avronaut
 *
 * @uses   \Avronaut\AvroFileMap
 * @uses   \Avronaut\AvroFilePath
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\AvroProtocol
 * @uses   \Avronaut\AvroReference
 * @uses   \Avronaut\Load\AvdlFileLoader
 * @uses   \Avronaut\Load\FileLoader
 * @uses   \Avronaut\Node\DecimalTypeNode
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\EnumConstantNode
 * @uses   \Avronaut\Node\EnumDeclarationNode
 * @uses   \Avronaut\Node\ErrorDeclarationNode
 * @uses   \Avronaut\Node\ErrorListNode
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
 * @uses   \Avronaut\Node\RecordDeclarationNode
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
 * @uses   \Avronaut\Render\Avdl\Handler\ArrayTypeNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\DecimalTypeNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\EnumConstantNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\EnumDeclarationNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\ErrorDeclarationNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\ErrorListNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\FieldDeclarationNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\FixedDeclarationNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\FormalParameterNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\FormalParametersNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\ImportStatementNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\LogicalTypeNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\MapTypeNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\MessageDeclarationNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\OnewayStatementNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\PrimitiveTypeNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\ProtocolDeclarationNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\RecordDeclarationNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\ReferenceTypeNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\ResultTypeNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\TypeNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\UnionTypeNodeHandler
 * @uses   \Avronaut\Render\Avdl\Handler\VariableDeclaratorNodeHandler
 * @uses   \Avronaut\Render\Avdl\HandlerContext
 * @uses   \Avronaut\Render\Diag\AstDumper
 * @uses   \Avronaut\Render\HandlerDelegate
 * @uses   \Avronaut\Render\Json\Handler\JsonArrayNodeHandler
 * @uses   \Avronaut\Render\Json\Handler\JsonFieldNodeHandler
 * @uses   \Avronaut\Render\Json\Handler\JsonObjectNodeHandler
 * @uses   \Avronaut\Render\Json\Handler\JsonValueNodeHandler
 * @uses   \Avronaut\Render\Json\HandlerAbstract
 * @uses   \Avronaut\Render\Json\HandlerContext
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
class AvdlRenderTest extends AvroTestCase
{
    /** @dataProvider provideInputOutput */
    public function test(string $input, string $output): void
    {
        $avronaut = new Avronaut();

        $source = sprintf('%s/../../../data/%s', __DIR__, $input);
        $target = sprintf('%s/../../../data/%s', __DIR__, $output);

        $writer = new BufferedWriter();
        $ref = new \ReflectionMethod($avronaut, 'createAvdlRenderer');

        /** @var Visitor $renderer */
        $renderer = $ref->invoke($avronaut, $writer);

        $proto = $avronaut->loadProtocol($source);
        $node = $proto->getFileMap()->getIterator()->current();
        $node->accept($renderer);

        $expect = file_get_contents($target);

        $this->assertEquals($expect, $writer->getBuffer());
    }

    public static function provideInputOutput(): array
    {
        return [
            ["proto-01-in.avdl", "proto-01-out.avdl"],
            ["proto-02-in.avdl", "proto-02-out.avdl"],
        ];
    }
}
