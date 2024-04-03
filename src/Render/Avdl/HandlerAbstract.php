<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Avdl;

use Avronaut\Node\DeclarationNode;
use Avronaut\Node\FieldDeclarationNode;
use Avronaut\Node\NamedDeclarationNode;
use Avronaut\Node\ProtocolDeclarationNode;
use Avronaut\Render\NodeHandler;
use Avronaut\Tree\Comments;
use Avronaut\Tree\Properties;
use Avronaut\Visitable;

/** @internal */
abstract class HandlerAbstract implements NodeHandler
{
    public function __construct(private readonly HandlerContext $context)
    {
    }

    public function getContext(): HandlerContext
    {
        return $this->context;
    }

    /**
     * Writes out:
     *  - The documentation block for Declaration nodes
     *  - The schema properties block for Declaration nodes
     * Does not write out:
     *  - Inline schema properties
     *
     * @param Visitable $node
     * @return bool
     * @throws \Exception
     */
    public function visit(Visitable $node): bool
    {
        if (!$node instanceof DeclarationNode) {
            return true;
        }

        $isProtoNode = $node instanceof ProtocolDeclarationNode;
        $isFieldNode = $node instanceof FieldDeclarationNode;

        $hasComments = !$node->getComments()->isEmpty();
        $hasProperties = !$node->getProperties()->isEmpty();

        if (!$isFieldNode || $hasComments || $hasProperties) {
            $this->write($isProtoNode ? '' : "\n");
        }

        $this->writeComments($node->getComments());
        $this->writePropertiesMultiLine($node->getProperties());

        if (!$node instanceof NamedDeclarationNode) {
            return true;
        }

        if (!$node->getNamespace()->isEmpty()) {
            $namespace = $node->getNamespace();

            /** @var NamedDeclarationNode|null $parent calms static analysis down. */
            $parent = $node->parentNode();

            if (!($parent != null && $parent->getNamespace()->equals($namespace))) {
                $this->write($this->indent(), '@namespace');
                $this->writeln('("', $namespace->getValue(), '")');
            }
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
    }

    protected function indent(): string
    {
        return str_repeat("\t", $this->getContext()->getDepth());
    }

    protected function stepIn(): void
    {
        $this->getContext()->stepIn();
    }

    protected function stepOut(): void
    {
        $this->getContext()->stepOut();
    }

    protected function write(string|float|int|null ...$args): void
    {
        $this->getContext()->getWriter()->write(...$args);
    }

    protected function writeln(string|float|int|null ...$args): void
    {
        $this->write(...$args);
        $this->write("\n");
    }

    protected function writeComments(Comments $comments): void
    {
        $block = '';

        foreach ($comments as $comment) {
            $block .= $comment->getValue();
            $block .= "\n\n";
        }

        $block = trim($block);
        if ($block != '') {
            $this->writeComment($block);
        }
    }

    protected function writeComment(string $comment): void
    {
        if (strpos($comment, "\n") > 0) {
            $this->writeCommentMultiLine($comment);
        } else {
            $this->writeCommentSingleLine($comment);
        }
    }

    private function writeCommentMultiLine(string $comment): void
    {
        $this->writeln($this->indent(), "/**");

        foreach (explode("\n", $comment) as $line) {
            $bullet = trim($line) == '' ? ' *' : ' * ';
            $this->writeln($this->indent(), $bullet, $line);
        }

        $this->writeln($this->indent(), " */");
    }

    private function writeCommentSingleLine(string $comment): void
    {
        $this->writeln($this->indent(), '/** ', $comment, " */");
    }

    protected function writePropertiesMultiLine(Properties $properties): void
    {
        foreach ($properties as $property) {
            $this->write($this->indent(), '@', $this->guardKeyword($property->getName()));
            $this->writeln('(', json_encode($property->getValue()), ')');
        }
    }

    protected function writePropertiesSingleLine(Properties $properties): void
    {
        foreach ($properties as $property) {
            $this->write('@', $this->guardKeyword($property->getName()));
            $this->write('(', json_encode($property->getValue()), ') ');
        }
    }

    protected function guardKeyword(string $keyword): string
    {
        return in_array($keyword, self::$keywords)
            ? sprintf('`%s`', $keyword)
            : $keyword;
    }

    protected static array $keywords = [
        "array", "boolean", "double", "enum", "error", "false", "fixed", "float",
        "idl", "import", "int", "long", "map", "oneway", "bytes", "schema", "string",
        "null", "protocol", "record", "throws", "true", "union", "void", "date",
        "time_ms", "timestamp_ms", "decimal", "local_timestamp_ms", "uuid"
    ];
}
