<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests;

use Avronaut\Avronaut;
use Avronaut\AvroFileMap;
use Avronaut\AvroProtocol;
use Avronaut\Write\BufferedWriter;

/**
 * @covers \Avronaut\AvroProtocol
 * @uses   \Avronaut\Avronaut
 * @uses   \Avronaut\AvroFileMap
 * @uses   \Avronaut\AvroFilePath
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\AvroReference
 * @uses   \Avronaut\Load\AvdlFileLoader
 * @uses   \Avronaut\Node\DeclarationNode
 * @uses   \Avronaut\Node\EnumConstantNode
 * @uses   \Avronaut\Node\EnumDeclarationNode
 * @uses   \Avronaut\Node\ErrorDeclarationNode
 * @uses   \Avronaut\Node\ErrorListNode
 * @uses   \Avronaut\Node\FieldDeclarationNode
 * @uses   \Avronaut\Node\FixedDeclarationNode
 * @uses   \Avronaut\Node\JsonArrayNode
 * @uses   \Avronaut\Node\JsonNode
 * @uses   \Avronaut\Node\JsonValueNode
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
 * @uses   \Avronaut\Render\Diag\AstDumper
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
class AvroProtocolTest extends AvroTestCase
{
    public function testFromFileMap(): void
    {
        $fileMap = new AvroFileMap();
        $proto = AvroProtocol::fromFileMap($fileMap);

        $this->assertEquals($fileMap, $proto->getFileMap());
    }

    public function testLoad(): void
    {
        $path = sprintf('%s/../data/proto-01-in.avdl', __DIR__);
        $proto = (new Avronaut())->loadProtocol($path);

        $this->assertTrue($proto->getFileMap()->size() > 0);
    }

    public function testDump(): void
    {
        $path = sprintf('%s/../data/proto-01-in.avdl', __DIR__);
        $proto = (new Avronaut())->loadProtocol($path);

        $writer = new BufferedWriter();
        $proto->dump($writer);

        $this->assertTrue(strlen($writer->getBuffer()) > 0);
    }
}
