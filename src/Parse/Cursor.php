<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Parse;

/** @internal */
interface Cursor
{
    public function peek(): Token;

    public function next(): Token;

    public function getCommentStack(): CommentStack;
}
