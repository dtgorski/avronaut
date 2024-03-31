<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Parse;

/** @internal */
class CommentAwareCursor implements Cursor
{
    private bool $ahead = false;
    private Token $token;

    public function __construct(
        private readonly \Generator $stream,
        private readonly CommentStack $stack = new CommentStack()
    ) {
        $this->token = new Token(Token::EOF, '', 0, 0);
    }

    public function peek(): Token
    {
        if (!$this->ahead) {
            $this->token = $this->current();
            $this->ahead = true;
        }
        return $this->token;
    }

    public function next(): Token
    {
        if (!$this->ahead) {
            $this->token = $this->current();
        }
        $this->ahead = false;
        $this->stream->next();

        return $this->token;
    }

    private function current(): Token
    {
        /** @var Token $token calms static analysis down. */
        $token = $this->stream->current();
        return $token;
    }

    public function getCommentStack(): CommentStack
    {
        return $this->stack;
    }
}
