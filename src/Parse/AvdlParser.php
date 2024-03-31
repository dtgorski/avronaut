<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Parse;

use Avronaut\AvroName;
use Avronaut\AvroNamespace;
use Avronaut\AvroReference;
use Avronaut\Node\ArrayTypeNode;
use Avronaut\Node\DecimalTypeNode;
use Avronaut\Node\DeclarationNode;
use Avronaut\Node\EnumConstantNode;
use Avronaut\Node\EnumDeclarationNode;
use Avronaut\Node\ErrorDeclarationNode;
use Avronaut\Node\ErrorListNode;
use Avronaut\Node\FieldDeclarationNode;
use Avronaut\Node\FixedDeclarationNode;
use Avronaut\Node\FormalParameterNode;
use Avronaut\Node\FormalParametersNode;
use Avronaut\Node\ImportStatementNode;
use Avronaut\Node\JsonNode;
use Avronaut\Node\LogicalTypeNode;
use Avronaut\Node\MapTypeNode;
use Avronaut\Node\MessageDeclarationNode;
use Avronaut\Node\NamedDeclarationNode;
use Avronaut\Node\OnewayStatementNode;
use Avronaut\Node\PrimitiveTypeNode;
use Avronaut\Node\ProtocolDeclarationNode;
use Avronaut\Node\RecordDeclarationNode;
use Avronaut\Node\ReferenceTypeNode;
use Avronaut\Node\ResultTypeNode;
use Avronaut\Node\TypeNode;
use Avronaut\Node\UnionTypeNode;
use Avronaut\Node\VariableDeclaratorNode;
use Avronaut\Tree\AstNode;
use Avronaut\Tree\Comments;
use Avronaut\Tree\Properties;
use Avronaut\Tree\Property;
use Avronaut\Type\ErrorType;
use Avronaut\Type\ImportType;
use Avronaut\Type\LogicalType;
use Avronaut\Type\NamedType;
use Avronaut\Type\PrimitiveType;

/**
 * ADVL (IDL) -> IDL AST
 * @internal
 */
class AvdlParser extends JsonParser
{
    /**
     * ProtocolDeclaration <EOF>
     *
     * @return AstNode
     * @throws \Exception
     */
    public function parse(): AstNode
    {
        $node = $this->parseProtocolDeclaration();
        $this->consume(Token::EOF);

        return $node;
    }

    /**
     * ( Property )* "protocol" Identifier ProtocolBody
     *
     * @return ProtocolDeclarationNode
     * @throws \Exception
     */
    protected function parseProtocolDeclaration(): ProtocolDeclarationNode
    {
        $propertyBag = $this->parsePropertiesWithNamespace();

        $this->consumeWithHint(Token::IDENT, self::hintProtocolKeyword, 'protocol');

        $ident = $this->parseAnyIdentifierWithHint(self::hintProtocolIdentifier);
        $name = AvroName::fromString($ident);

        $node = new ProtocolDeclarationNode($name, $propertyBag->getProperties());
        $node->setNamespace($propertyBag->getNamespace());
        $node->setComments($this->drainCommentStack());

        $node->addNode(...$this->parseProtocolBody($propertyBag->getNamespace()));
        return $node;
    }

    /**
     * "{" ( Imports | Options )*  "}"
     *
     * @return AstNode[]
     * @throws \Exception
     */
    protected function parseProtocolBody(AvroNamespace $namespace): array
    {
        $nodes = [];
        $this->consumeWithHint(Token::LBRACE, self::hintProtocolBodyOpen);

        while (!$this->expect(Token::RBRACE)) {
            if ($this->expect(Token::IDENT, 'import')) {
                $nodes[] = $this->parseImportStatement();
                continue;
            }

            $node = $this->parseDeclaration();

            if ($node instanceof NamedDeclarationNode) {
                if ($node->getNamespace()->isEmpty()) {
                    $node->setNamespace($namespace);
                }
            }
            $nodes[] = $node;
        }

        $this->consumeWithHint(Token::RBRACE, self::hintProtocolBodyClose);
        return $nodes;
    }

    /**
     * ( ImportIdl | ImportProtocol | ImportSchema )*
     *
     * @return ImportStatementNode
     * @throws \Exception
     */
    protected function parseImportStatement(): ImportStatementNode
    {
        $types = ImportType::values();
        $this->consume(Token::IDENT, 'import');
        $type = $this->consumeWithHint(Token::IDENT, self::hintImportTypeName, ...$types)->getLoad();
        $path = $this->consumeWithHint(Token::STRING, self::hintImportFilePath)->getLoad();
        $this->parseSemicolon();

        return new ImportStatementNode(ImportType::from($type), $path);
    }

    /**
     * ( Property )* ( NamedDeclaration | MessageDeclaration ) )*
     *
     * @return DeclarationNode
     * @throws \Exception
     */
    protected function parseDeclaration(): DeclarationNode
    {
        $propertyBag = $this->parsePropertiesWithNamespace();

        if ($this->expect(Token::IDENT, ...NamedType::values())) {
            $node = $this->parseNamedDeclaration($propertyBag->getProperties());
            $node->setNamespace($propertyBag->getNamespace());

            return $node;
        }
        return $this->parseMessageDeclaration($propertyBag->getProperties());
    }

    /**
     * ResultType Identifier FormalParameters ( "oneway" | "throws" ErrorList )? ";"
     *
     * @param Properties $properties
     * @return MessageDeclarationNode
     * @throws \Exception
     */
    protected function parseMessageDeclaration(Properties $properties): MessageDeclarationNode
    {
        $type = $this->parseResultType();
        $name = AvroName::fromString($this->parseAnyIdentifier());
        $node = new MessageDeclarationNode($name, $properties);
        $node->addNode($type, $this->parseFormalParameters());
        $node->setComments($this->drainCommentStack());

        if ($this->expect(Token::IDENT, 'throws')) {
            $node->addNode($this->parseErrorList());
        } elseif ($this->expect(Token::IDENT, 'oneway')) {
            $node->addNode($this->parseOnewayStatement());
        }
        $this->parseSemicolon();
        return $node;
    }

    /**
     *    ( "(" ( FormalParameter ( "," FormalParameter )* )? ")" )
     *
     * @return FormalParametersNode
     * @throws \Exception
     */
    protected function parseFormalParameters(): FormalParametersNode
    {
        $node = new FormalParametersNode();
        $this->consume(Token::LPAREN);

        if (!$this->expect(Token::RPAREN)) {
            $node->addNode($this->parseFormalParameter());
            while ($this->expect(Token::COMMA)) {
                $this->consume(Token::COMMA);
                $node->addNode($this->parseFormalParameter());
            }
        }
        $this->consume(Token::RPAREN);
        return $node;
    }

    /**
     * Type VariableDeclarator
     *
     * @return FormalParameterNode
     * @throws \Exception
     */
    protected function parseFormalParameter(): FormalParameterNode
    {
        $node = new FormalParameterNode();
        $type = $this->parseType();
        $node->addNode($type);
        $node->addNode($this->parseVariableDeclarator($type));
        return $node;
    }

    /**
     * ReferenceType ( "," ReferenceType )*
     *
     * @return ErrorListNode
     * @throws \Exception
     */
    protected function parseErrorList(): ErrorListNode
    {
        $token = $this->consume(Token::IDENT, ...ErrorType::values());
        $node = new ErrorListNode(ErrorType::from($token->getLoad()));
        $node->addNode((new TypeNode())->addNode($this->parseReferenceType(Properties::fromEmpty())));

        while ($this->expect(Token::COMMA)) {
            $this->consume(Token::COMMA);
            $node->addNode((new TypeNode())->addNode($this->parseReferenceType(Properties::fromEmpty())));
        }
        return $node;
    }

    /**
     * "oneway"
     *
     * @return TypeNode
     * @throws \Exception
     */
    protected function parseOnewayStatement(): TypeNode
    {
        $this->consume(Token::IDENT, 'oneway');
        $typeNode = new TypeNode();
        $typeNode->addNode(new OnewayStatementNode());
        return $typeNode;
    }

    /**
     * ( RecordDeclaration | ErrorDeclaration | EnumDeclaration | FixedDeclaration )
     *
     * @param Properties $properties
     * @return NamedDeclarationNode
     * @throws \Exception
     */
    protected function parseNamedDeclaration(Properties $properties): NamedDeclarationNode
    {
        if ($this->expect(Token::IDENT, 'error')) {
            return $this->parseErrorDeclaration($properties);
        }
        if ($this->expect(Token::IDENT, 'enum')) {
            return $this->parseEnumDeclaration($properties);
        }
        if ($this->expect(Token::IDENT, 'fixed')) {
            return $this->parseFixedDeclaration($properties);
        }
        return $this->parseRecordDeclaration($properties);
    }

    /**
     * "fixed" Identifier "(" <INTEGER> ")" ";"
     *
     * @param Properties $properties
     * @return FixedDeclarationNode
     * @throws \Exception
     */
    protected function parseFixedDeclaration(Properties $properties): FixedDeclarationNode
    {
        $this->consume(Token::IDENT, 'fixed');
        $name = AvroName::fromString($this->parseAnyIdentifier());
        $this->consume(Token::LPAREN);
        $value = $this->consume(Token::NUMBER)->getLoad();
        $node = new FixedDeclarationNode($name, (int) $value, $properties);
        $node->setComments($this->drainCommentStack());
        $this->consume(Token::RPAREN);
        $this->parseSemicolon();
        return $node;
    }

    /**
     * "record" Identifier "{" (FieldDeclaration)* "}"
     *
     * @param Properties $properties
     * @return RecordDeclarationNode
     * @throws \Exception
     */
    protected function parseRecordDeclaration(Properties $properties): RecordDeclarationNode
    {
        $this->consume(Token::IDENT, 'record');

        $ident = $this->parseAnyIdentifier();
        $name = AvroName::fromString($ident);

        $node = new RecordDeclarationNode($name, $properties);
        $node->setComments($this->drainCommentStack());
        $this->consume(Token::LBRACE);

        while (!$this->expect(Token::RBRACE)) {
            $node->addNode($this->parseFieldDeclaration());
        }
        $this->consume(Token::RBRACE);
        return $node;
    }

    /**
     * "error" Identifier "{" (FieldDeclaration)* "}"
     *
     * @param Properties $properties
     * @return ErrorDeclarationNode
     * @throws \Exception
     */
    protected function parseErrorDeclaration(Properties $properties): ErrorDeclarationNode
    {
        $this->consume(Token::IDENT, 'error');

        $ident = $this->parseAnyIdentifier();
        $name = AvroName::fromString($ident);

        $node = new ErrorDeclarationNode($name, $properties);
        $node->setComments($this->drainCommentStack());
        $this->consume(Token::LBRACE);

        while (!$this->expect(Token::RBRACE)) {
            $node->addNode($this->parseFieldDeclaration());
        }
        $this->consume(Token::RBRACE);
        return $node;
    }

    /**
     * "enum" Identifier "{" EnumBody "}" ( <EQ> Identifier )
     *
     * @param Properties $properties
     * @return EnumDeclarationNode
     * @throws \Exception
     */
    protected function parseEnumDeclaration(Properties $properties): EnumDeclarationNode
    {
        $default = '';
        $this->consume(Token::IDENT, 'enum');
        $ident = AvroName::fromString($this->parseAnyIdentifier());
        $this->consume(Token::LBRACE);
        $body = $this->parseEnumBody();
        $this->consume(Token::RBRACE);

        if ($this->expect(Token::EQ)) {
            $this->consume(Token::EQ);
            $default = $this->parseAnyIdentifier();

            // FIXME: check if default key exists.

            $this->parseSemicolon();
        }

        $node = new EnumDeclarationNode($ident, $default, $properties);
        $node->setComments($this->drainCommentStack());

        $node->addNode(...$body);
        return $node;
    }

    /**
     * ( Identifier ( "," Identifier )* )?
     *
     * @return EnumConstantNode[]
     * @throws \Exception
     */
    protected function parseEnumBody(): array
    {
        $nodes = [];
        if ($this->expect(Token::IDENT) || $this->expect(Token::TICK)) {
            $name = AvroName::fromString($this->parseAnyIdentifier());
            $nodes[] = new EnumConstantNode($name);

            while ($this->expect(Token::COMMA)) {
                $this->consume(Token::COMMA);

                $name = AvroName::fromString($this->parseAnyIdentifier());
                $nodes[] = new EnumConstantNode($name);
            }
        }
        return $nodes;
    }

    /**
     * ( ( Property )* Type VariableDeclarator ( "," VariableDeclarator )* ";" )*
     *
     * @return FieldDeclarationNode
     * @throws \Exception
     */
    protected function parseFieldDeclaration(): FieldDeclarationNode
    {
        $node = new FieldDeclarationNode();
        $type = $this->parseType();

        $node->addNode($type);
        $node->addNode($this->parseVariableDeclarator($type));
        $node->setComments($this->drainCommentStack());

        while ($this->expect(Token::COMMA)) {
            $this->consume(Token::COMMA);
            $node->addNode($this->parseVariableDeclarator($type));
        }
        $this->parseSemicolon();
        return $node;
    }

    protected function ensureDefaultValueMatchesType(JsonNode $json, AstNode $type): void
    {
        // FIXME: implement.
    }

    /**
     * ( Property )* Identifier ( <EQ> JSONValue )?
     *
     * @param AstNode $type
     * @return VariableDeclaratorNode
     * @throws \Exception
     */
    protected function parseVariableDeclarator(AstNode $type): VariableDeclaratorNode
    {
        $props = $this->parsePropertiesSkipNamespace();
        $ident = $this->parseAnyIdentifier();
        $name = AvroName::fromString($ident);
        $node = new VariableDeclaratorNode($name, $props);

        if ($this->expect(Token::EQ)) {
            $this->consume(Token::EQ);
            $json = parent::parseJson();
            $this->ensureDefaultValueMatchesType($json, $type);

            $node->addNode($json);
        }
        return $node;
    }

    /**
     * "void" | Type
     *
     * @return ResultTypeNode
     * @throws \Exception
     */
    protected function parseResultType(): ResultTypeNode
    {
        if ($this->expect(Token::IDENT, 'void')) {
            $this->consume(Token::IDENT);
            return new ResultTypeNode(true);
        }
        $node = new ResultTypeNode(false);
        $node->addNode($this->parseType());
        return $node;
    }

    /**
     * ( Property )* ( ReferenceType | PrimitiveType | UnionType | ArrayType | MapType | DecimalType ) "?"?
     *
     * @return TypeNode
     * @throws \Exception
     */
    protected function parseType(): TypeNode
    {
        $properties = $this->parsePropertiesSkipNamespace();

        $node = $this->parsePrimitiveType($properties);
        $node = $node ?? $this->parseLogicalType($properties);
        $node = $node ?? $this->parseUnionType($properties);
        $node = $node ?? $this->parseArrayType($properties);
        $node = $node ?? $this->parseMapType($properties);
        $node = $node ?? $this->parseDecimalType($properties);
        $node = $node ?? $this->parseReferenceType($properties);

        // FIXME: check properties
        if ($this->expect(Token::QMARK)) {
            $this->consume(Token::QMARK);
            $type = new TypeNode(true);
        } else {
            $type = new TypeNode();
        }
        $type->addNode($node);
        return $type;
    }

    /**
     * "boolean" | "bytes" | "int" | "string" | "float" | ...
     *
     * @param Properties $properties
     * @return PrimitiveTypeNode|null
     * @throws \Exception
     */
    protected function parsePrimitiveType(Properties $properties): PrimitiveTypeNode|null
    {
        if ($this->expect(Token::IDENT, ...PrimitiveType::values())) {
            return new PrimitiveTypeNode(PrimitiveType::from($this->parseIdentifier()), $properties);
        }
        return null;
    }

    /**
     * @param Properties $properties
     * @return LogicalTypeNode|null
     * @throws \Exception
     */
    protected function parseLogicalType(Properties $properties): LogicalTypeNode|null
    {
        if ($this->expect(Token::IDENT, ...LogicalType::values())) {
            return new LogicalTypeNode(LogicalType::from($this->parseIdentifier()), $properties);
        }
        return null;
    }

    /**
     * "decimal" "(" <INTEGER>, <INTEGER> ")"
     *
     * @param Properties $properties
     * @return DecimalTypeNode|null
     * @throws \Exception
     */
    protected function parseDecimalType(Properties $properties): DecimalTypeNode|null
    {
        if (!$this->expect(Token::IDENT, "decimal")) {
            return null;
        }
        $this->consume(Token::IDENT);
        $this->consume(Token::LPAREN);
        $precToken = $this->peek();
        $precision = (int) $this->consume(Token::NUMBER)->getLoad();
        $this->consume(Token::COMMA);
        $scaleToken = $this->peek();
        $scale = (int) $this->consume(Token::NUMBER)->getLoad();
        $this->consume(Token::RPAREN);

        if ($precision < 0) {
            $this->throwException($precToken, 'unexpected negative decimal type precision');
        }
        if ($scale < 0 || $scale > $precision) {
            $this->throwException($scaleToken, 'unexpected invalid decimal type scale');
        }
        return new DecimalTypeNode($precision, $scale, $properties);
    }

    /**
     * "array" "<" Type ">"
     *
     * @param Properties $properties
     * @return ArrayTypeNode|null
     * @throws \Exception
     */
    protected function parseArrayType(Properties $properties): ArrayTypeNode|null
    {
        if (!$this->expect(Token::IDENT, 'array')) {
            return null;
        }
        $this->consume(Token::IDENT);
        $this->consume(Token::LT);
        $node = new ArrayTypeNode($properties);
        $node->addNode($this->parseType());
        $this->consume(Token::GT);
        return $node;
    }

    /**
     * "map" "<" Type ">"
     *
     * @param Properties $properties
     * @return MapTypeNode|null
     * @throws \Exception
     */
    protected function parseMapType(Properties $properties): MapTypeNode|null
    {
        if (!$this->expect(Token::IDENT, 'map')) {
            return null;
        }
        $this->consume(Token::IDENT);
        $this->consume(Token::LT);
        $node = new MapTypeNode($properties);
        $node->addNode($this->parseType());
        $this->consume(Token::GT);
        return $node;
    }

    /**
     * "union" "{" Type ( "," Type )* "}"
     *
     * @param Properties $properties
     * @return UnionTypeNode|null
     * @throws \Exception
     */
    protected function parseUnionType(Properties $properties): UnionTypeNode|null
    {
        if (!$this->expect(Token::IDENT, 'union')) {
            return null;
        }
        $this->consume(Token::IDENT);
        $this->consume(Token::LBRACE);
        $node = new UnionTypeNode($properties);
        $node->addNode($this->parseType());

        while ($this->expect(Token::COMMA)) {
            $this->consume(Token::COMMA);
            $node->addNode($this->parseType());
        }
        $this->consume(Token::RBRACE);
        return $node;
    }

    /**
     * ( Identifier ( "." Identifier )* )
     *
     * @param Properties $properties
     * @return ReferenceTypeNode
     * @throws \Exception
     */
    protected function parseReferenceType(Properties $properties): ReferenceTypeNode
    {
        $parts = [];
        $parts[] = $this->parseAnyIdentifierWithHint(self::hintReferenceIdentifier);

        while ($this->expect(Token::DOT)) {
            $this->consume(Token::DOT);
            $parts[] = $this->parseAnyIdentifierWithHint(self::hintReferenceIdentifier);
        }

        $name = join('.', $parts);
        return new ReferenceTypeNode(AvroReference::fromString($name), $properties);
    }

    /**
     * PropertyName "(" JSONValue ")"
     *
     * @return Property
     * @throws \Exception
     */
    protected function parseProperty(): Property
    {
        $name = $this->parsePropertyName();
        $this->consume(Token::LPAREN);

        /** @var mixed $json */
        $json = json_decode(json_encode(parent::parseJson()));

        $this->consume(Token::RPAREN);
        return new Property($name, $json);
    }

    /**
     * ( "@" PropertyName "(" JSONValue ")" )*
     *
     * @return Properties
     * @throws \Exception
     */
    protected function parsePropertiesSkipNamespace(): Properties
    {
        $properties = [];
        while ($this->expect(Token::AT)) {
            $this->consume(Token::AT);
            $property = $this->parseProperty();

            if ($property->getName() != 'namespace') {
                $properties[$property->getName()] = $property;
            }
        }
        return Properties::fromKeyValue($properties);
    }

    /**
     * ( "@" PropertyName "(" JSONValue ")" )*
     *
     * @return PropertiesWithNamespace
     * @throws \Exception
     */
    protected function parsePropertiesWithNamespace(): PropertiesWithNamespace
    {
        $properties = [];
        $namespace = '';

        while ($this->expect(Token::AT)) {
            $this->consume(Token::AT);
            $token = $this->peek();
            $property = $this->parseProperty();

            if ($property->getName() != 'namespace') {
                $properties[$property->getName()] = $property;
                continue;
            }
            if (is_string($property->getJson())) {
                $namespace = (string) $property->getJson();
                continue;
            }
            $this->throwUnexpectedTokenWithHint(
                $token,
                self::hintProtocolNamespace
            );
        }
        return new PropertiesWithNamespace(
            Properties::fromKeyValue($properties),
            AvroNamespace::fromString($namespace)
        );
    }

    /**
     * <IDENTIFIER> (<DASH> <IDENTIFIER>)*
     *
     * @return string
     * @throws \Exception
     */
    protected function parsePropertyName(): string
    {
        $ident = $this->parseAnyIdentifier();
        while ($this->expect(Token::DASH)) {
            $this->consume(Token::DASH);
            $ident = $ident . '-' . $this->parseAnyIdentifier();
        }
        return $ident;
    }

    /**
     * @param string $hint Informative part for a possible error message.
     * @return string
     * @throws \Exception
     */
    protected function parseIdentifierWithHint(string $hint): string
    {
        return $this->consumeWithHint(Token::IDENT, $hint)->getLoad();
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function parseIdentifier(): string
    {
        return $this->parseIdentifierWithHint('<identifier>');
    }

    /**
     * @param string $hint Informative part for a possible error message.
     * @return string
     * @throws \Exception
     */
    protected function parseAnyIdentifierWithHint(string $hint): string
    {
        if ($this->expect(Token::TICK)) {
            $this->consume(Token::TICK);
            $ident = $this->parseIdentifierWithHint($hint);
            $this->consume(Token::TICK);
            return $ident;
        }
        return $this->parseIdentifierWithHint($hint);
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function parseAnyIdentifier(): string
    {
        if ($this->expect(Token::TICK)) {
            $this->consume(Token::TICK);
            $ident = $this->parseIdentifier();
            $this->consume(Token::TICK);
            return $ident;
        }
        return $this->parseIdentifier();
    }

    /** @throws \Exception */
    protected function parseSemicolon(): void
    {
        $this->consumeWithHint(Token::SEMICOL, self::hintTrailingSemicolon);
    }

    /** @return Comments */
    private function drainCommentStack(): Comments
    {
        return Comments::fromKeyValue($this->getCursor()->getCommentStack()->drain());
    }

    // @formatter:off
    // phpcs:disable
    private const
        // Message "expected ..."
        hintProtocolNamespace   = "@namespace(...) property value to be string",
        hintProtocolKeyword     = "@namespace(...) property and 'protocol' keyword",
        hintProtocolIdentifier  = "protocol name <identifier>",
        hintProtocolBodyOpen    = "protocol body opening brace '{'",
        hintProtocolBodyClose   = "protocol body closing brace '}'",
        hintImportFilePath      = "import file path in double quotes",
        hintTrailingSemicolon   = "trailing semicolon ';'",
        hintReferenceIdentifier = "reference type name <identifier>",

        // TODO: implement protocol and schema imports.
        #hintImportTypeName     = "import type to be one of 'idl', 'protocol' or 'schema'",
        hintImportTypeName      = "import type to be 'idl' ('protocol' or 'schema' unsupported)"
    ;
    // phpcs:enable
    // @formatter:on
}

/** @codeCoverageIgnore */
// phpcs:disable
class PropertiesWithNamespace
{
    public function __construct(
        private readonly Properties $properties,
        private readonly AvroNamespace $namespace
    ) {
    }

    public function getProperties(): Properties
    {
        return $this->properties;
    }

    public function getNamespace(): AvroNamespace
    {
        return $this->namespace;
    }
}
