<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Parse;

use Avronaut\Tree\Comment;

/** @internal */
class CommentSaveCursor extends CommentAwareCursor
{
    public function peek(): Token
    {
        while ($token = parent::peek()) {
            if ($token->is(Token::COMLINE)) { // Line comments skipped
                parent::next();
                continue;
            }
            if ($token->is(Token::COMBLCK)) {
                $this->getCommentStack()->push(Comment::fromString($token->getLoad()));
                parent::next();
                continue;
            }
            break;
        }
        return parent::peek();
    }

    public function next(): Token
    {
        while ($token = parent::peek()) {
            if ($token->is(Token::COMLINE)) { // Line comments skipped
                parent::next();
                continue;
            }
            if ($token->is(Token::COMBLCK)) {
                $this->getCommentStack()->push(Comment::fromString($token->getLoad()));
                parent::next();
                continue;
            }
            break;
        }
        return parent::next();
    }
}
