<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Parse;

use Avronaut\Tree\Comment;

/** @internal */
class CommentStack
{
    /** @var Comment[] $comments */
    private array $comments = [];

    public function push(Comment $comment): void
    {
        $this->comments[] = $comment;
    }

    public function size(): int
    {
        return sizeof($this->comments);
    }

    /** @return Comment[] */
    public function drain(): array
    {
        $comments = $this->comments;
        $this->comments = [];
        return $comments;
    }
}
