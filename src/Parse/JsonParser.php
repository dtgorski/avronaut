<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Parse;

use Avronaut\AvroName;
use Avronaut\Node\JsonArrayNode;
use Avronaut\Node\JsonFieldNode;
use Avronaut\Node\JsonNode;
use Avronaut\Node\JsonObjectNode;
use Avronaut\Node\JsonValueNode;
use Avronaut\Tree\AstNode;
use Avronaut\Tree\Node;

/**
 * JSON -> JSON AST
 * @internal
 */
class JsonParser extends ParserBase
{
    /**
     * @return AstNode
     * @throws \Exception
     */
    public function parse(): AstNode
    {
        return $this->parseJson();
    }

    /**
     * @return JsonNode
     * @throws \Exception
     */
    protected function parseJson(): JsonNode
    {
        // @formatter:off
        // phpcs:disable
        switch (($token = $this->peek())->getType()) {
            case Token::LBRACK: return $this->parseJsonArray();
            case Token::LBRACE: return $this->parseJsonObject();
            case Token::STRING: return $this->parseJsonString();
            case Token::NUMBER: return $this->parseJsonNumber();
            case Token::IDENT:
                switch ($token->getLoad()) {
                    case 'true':
                    case 'false': return $this->parseJsonBool();
                    case 'null':  return $this->parseJsonNull();
                    default:      $this->throwUnexpectedTokenWithHint($token, 'valid JSON');
                }
            default: $this->throwUnexpectedToken($token);
        }
        // phpcs:enable
        // @formatter:on
    }

    /**
     * @return JsonValueNode
     * @throws \Exception
     */
    protected function parseJsonString(): JsonValueNode
    {
        $token = $this->consume(Token::STRING);
        return new JsonValueNode($token->getLoad(), $token->getPosition());
    }

    /**
     * @return JsonValueNode
     * @throws \Exception
     */
    protected function parseJsonNumber(): JsonValueNode
    {
        $token = $this->consume(Token::NUMBER);
        return new JsonValueNode((float) $token->getLoad(), $token->getPosition());
    }

    /**
     * @return JsonValueNode
     * @throws \Exception
     */
    protected function parseJsonBool(): JsonValueNode
    {
        $token = $this->consume(Token::IDENT, 'true', 'false');
        return new JsonValueNode($token->getLoad() === 'true', $token->getPosition());
    }

    /**
     * @return JsonValueNode
     * @throws \Exception
     */
    protected function parseJsonNull(): JsonValueNode
    {
        $token = $this->consume(Token::IDENT, 'null');
        return new JsonValueNode(null, $token->getPosition());
    }

    /**
     * @return JsonArrayNode
     * @throws \Exception
     */
    protected function parseJsonArray(): JsonArrayNode
    {
        $token = $this->consume(Token::LBRACK);
        $node = new JsonArrayNode($token->getPosition());

        if (!$this->expect(Token::RBRACK)) {
            $node->addNode($this->parseJson());

            while ($this->expect(Token::COMMA)) {
                $this->consume(Token::COMMA);
                $node->addNode($this->parseJson());
            }
        }
        $this->consume(Token::RBRACK);
        return $node;
    }

    /**
     * @return JsonObjectNode
     * @throws \Exception
     */
    protected function parseJsonObject(): JsonObjectNode
    {
        $token = $this->consume(Token::LBRACE);
        $node = new JsonObjectNode($token->getPosition());

        if (!$this->expect(Token::RBRACE)) {
            $node->addNode($this->parseJsonField());

            while ($this->expect(Token::COMMA)) {
                $this->consume(Token::COMMA);
                $node->addNode($this->parseJsonField());
            }
        }
        $this->consume(Token::RBRACE);
        return $node;
    }

    /**
     * @return JsonFieldNode
     * @throws \Exception
     */
    protected function parseJsonField(): JsonFieldNode
    {
        $token = $this->consume(Token::STRING);
        $node = new JsonFieldNode(AvroName::fromString($token->getLoad()), $token->getPosition());
        $this->consume(Token::COLON);
        $node->addNode($this->parseJson());
        return $node;
    }
}
