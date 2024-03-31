<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Parse;

/** @internal */
class CommentSkipCursor extends CommentAwareCursor
{
    public function peek(): Token
    {
        while ($this->isComment(parent::peek())) {
            parent::next();
        }
        return parent::peek();
    }

    public function next(): Token
    {
        while ($this->isComment(parent::peek())) {
            parent::next();
        }
        return parent::next();
    }

    private function isComment(Token $token): bool
    {
        return $token->is(Token::COMLINE)
            || $token->is(Token::COMBLCK);
    }
}
