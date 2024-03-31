<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tests\Load;

use Avronaut\AvroName;
use Avronaut\AvroFileMap;
use Avronaut\AvroFilePath;
use Avronaut\Node\ProtocolDeclarationNode;
use Avronaut\Load\AvdlFileLoader;
use Avronaut\Tests\AvroTestCase;

/**
 * @covers \Avronaut\Load\AvdlFileLoader
 * @uses   \Avronaut\AvroFileMap
 * @uses   \Avronaut\AvroFilePath
 * @uses   \Avronaut\AvroName
 * @uses   \Avronaut\AvroNamespace
 * @uses   \Avronaut\AvroReference
 * @uses   \Avronaut\Load\FileLoader
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
 * @uses   \Avronaut\Render\Walker
 * @uses   \Avronaut\Shared\EntityMap
 * @uses   \Avronaut\Shared\Position
 * @uses   \Avronaut\Tree\AstNode
 * @uses   \Avronaut\Tree\Comment
 * @uses   \Avronaut\Tree\Comments
 * @uses   \Avronaut\Tree\Properties
 * @uses   \Avronaut\Tree\Property
 * @uses   \Avronaut\Tree\TreeNode
 */
class AvdlFileLoaderTest extends AvroTestCase
{
    public function testProtoLoad(): void
    {
        $path = sprintf('%s/../../data/proto-01-in.avdl', __DIR__);
        $filename = AvroFilePath::fromString($path);

        $loader = new AvdlFileLoader();
        $fileMap = $loader->load($filename);

        $this->assertInstanceOf(ProtocolDeclarationNode::class, $fileMap->getIterator()->current());
    }

    public function testProtoExists(): void
    {
        $path = sprintf('%s/../../data/proto-01-in.avdl', __DIR__);
        $filename = AvroFilePath::fromString($path);

        $protoNode = new ProtocolDeclarationNode(AvroName::fromString('foo'));

        $fileMap = new AvroFileMap();
        $fileMap->set($filename, $protoNode);

        $loader = new AvdlFileLoader($fileMap);
        $loader->load($filename);

        $this->assertEquals(1, sizeof($fileMap->asArray()));
        $this->assertSame($protoNode, $fileMap->getIterator()->current());
    }

    public function testThrowsExceptionWhenCanNotReadFile(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/unable to read file/');

        $loader = new AvdlFileLoader();
        $loader->load(AvroFilePath::fromString('/tmp/'));
    }

    public function testThrowsExceptionWhenCanNotParseFile(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/expected @namespace/');

        $loader = new AvdlFileLoader();
        $loader->load(AvroFilePath::fromString(__FILE__));
    }
}
