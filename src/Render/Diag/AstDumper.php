<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Diag;

use Avronaut\Node\DecimalTypeNode;
use Avronaut\Node\JsonNode;
use Avronaut\Node\LogicalTypeNode;
use Avronaut\Node\MessageDeclarationNode;
use Avronaut\Node\NamedDeclarationNode;
use Avronaut\Node\PrimitiveTypeNode;
use Avronaut\Node\ProtocolDeclarationNode;
use Avronaut\Node\RecordDeclarationNode;
use Avronaut\Node\ReferenceTypeNode;
use Avronaut\Node\ResultTypeNode;
use Avronaut\Node\TypeNode;
use Avronaut\Node\VariableDeclaratorNode;
use Avronaut\Tree\AstNode;
use Avronaut\Tree\Node;
use Avronaut\Visitable;
use Avronaut\Visitor;
use Avronaut\Write\StandardWriter;
use Avronaut\Write\Writer;

class AstDumper implements Visitor
{
    public function __construct(
        private readonly Writer $writer = new StandardWriter()
    ) {
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        $writer = $this->writer;

        /** @var AstNode $node calms static analysis down. */
        $parts = explode('\\', get_class($node));
        $name = $parts[sizeof($parts) - 1];
        $edges = $this->edges($node);

        $line = sprintf('%s%s', $edges, $name);
        $line = sprintf('%s%s', $line, str_repeat(' ', 56));
        $line = substr($line, 0, 56 + substr_count($line, "\xE2") * 2);

        $nullableType = function (AstNode $node): string {
            $type = $node->parentNode();
            return $type instanceof TypeNode && $type->isNullable() ? '?' : '';
        };

        $writer->write($line, ':');

        if ($node instanceof NamedDeclarationNode && !$node->getNamespace()->isEmpty()) {
            $writer->write(' @namespace("', $node->getNamespace()->getValue(), '")');
        }
        if (!$node->getProperties()->isEmpty()) {
            $writer->write(' @', trim(json_encode($node->getProperties())));
        }

        switch (true) {
            case $node instanceof DecimalTypeNode:
                $writer->write(' (', $node->getPrecision(), ', ', $node->getScale(), ')');
                break;

            case $node instanceof JsonNode:
                $writer->write(' ', json_encode($node));
                break;

            case $node instanceof LogicalTypeNode:
                $writer->write(' ', $node->getType()->value);
                break;

            case $node instanceof PrimitiveTypeNode:
                $writer->write(' ', $node->getType()->value);
                $writer->write($nullableType($node));
                break;

            case $node instanceof ReferenceTypeNode:
                $writer->write(' ', $node->getReference()->getQualifiedName());
                $writer->write($nullableType($node));
                break;

            case $node instanceof ResultTypeNode && $node->isVoid():
                $writer->write(' void');
                break;

            case $node instanceof TypeNode && $node->isNullable():
                $writer->write(' (nullable)');
                break;

            case $node instanceof MessageDeclarationNode:
            case $node instanceof ProtocolDeclarationNode:
            case $node instanceof RecordDeclarationNode:
            case $node instanceof VariableDeclaratorNode:
                $writer->write(' ', $node->getName()->getValue());
                break;
        }

        $this->writer->write("\n");
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
    }

    private function edges(Node $node): string
    {
        $edge = $node->parentNode() ? ($node->nextNode() ? '├── ' : '└── ') : '';
        while ($node = $node->parentNode()) {
            $edge = sprintf('%s%s', ($node->nextNode() ? '│   ' : '    '), $edge);
        }
        return $edge;
    }
}
