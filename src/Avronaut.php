<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut;

use Avronaut\Load\AvdlFileLoader;
use Avronaut\Render\HandlerDelegate;
use Avronaut\Write\BufferedWriter;
use Avronaut\Write\Writer;

class Avronaut
{
    /** @throws \Exception */
    public function loadProtocol(string $filename): AvroProtocol
    {
        $filename = AvroFilePath::fromString($filename);
        $loader = new AvdlFileLoader();

        return AvroProtocol::fromFileMap($loader->load($filename));
    }

    protected function createAvdlRenderer(Writer $writer = new BufferedWriter()): HandlerDelegate
    {
        /** @var Render\HandlerDelegate $jsonize */
        $jsonize = $this->createJsonRenderer($writer);

        $ctx = new Render\Avdl\HandlerContext($writer);

        return new Render\HandlerDelegate([
            new Render\Avdl\Handler\ArrayTypeNodeHandler($ctx),
            new Render\Avdl\Handler\DecimalTypeNodeHandler($ctx),
            new Render\Avdl\Handler\EnumConstantNodeHandler($ctx),
            new Render\Avdl\Handler\EnumDeclarationNodeHandler($ctx),
            new Render\Avdl\Handler\ErrorDeclarationNodeHandler($ctx),
            new Render\Avdl\Handler\ErrorListNodeHandler($ctx),
            new Render\Avdl\Handler\FieldDeclarationNodeHandler($ctx),
            new Render\Avdl\Handler\FixedDeclarationNodeHandler($ctx),
            new Render\Avdl\Handler\FormalParameterNodeHandler($ctx),
            new Render\Avdl\Handler\FormalParametersNodeHandler($ctx),
            new Render\Avdl\Handler\ImportStatementNodeHandler($ctx),
            new Render\Avdl\Handler\LogicalTypeNodeHandler($ctx),
            new Render\Avdl\Handler\MapTypeNodeHandler($ctx),
            new Render\Avdl\Handler\MessageDeclarationNodeHandler($ctx),
            new Render\Avdl\Handler\OnewayStatementNodeHandler($ctx),
            new Render\Avdl\Handler\PrimitiveTypeNodeHandler($ctx),
            new Render\Avdl\Handler\ProtocolDeclarationNodeHandler($ctx),
            new Render\Avdl\Handler\RecordDeclarationNodeHandler($ctx),
            new Render\Avdl\Handler\ReferenceTypeNodeHandler($ctx),
            new Render\Avdl\Handler\ResultTypeNodeHandler($ctx),
            new Render\Avdl\Handler\TypeNodeHandler($ctx),
            new Render\Avdl\Handler\UnionTypeNodeHandler($ctx),
            new Render\Avdl\Handler\VariableDeclaratorNodeHandler($ctx),
            ...$jsonize->getHandlers()
        ]);
    }

    protected function createJsonRenderer(Writer $writer): Visitor
    {
        $ctx = new Render\Json\HandlerContext($writer);

        return new Render\HandlerDelegate([
            new Render\Json\Handler\JsonArrayNodeHandler($ctx),
            new Render\Json\Handler\JsonFieldNodeHandler($ctx),
            new Render\Json\Handler\JsonObjectNodeHandler($ctx),
            new Render\Json\Handler\JsonValueNodeHandler($ctx),
        ]);
    }
}
