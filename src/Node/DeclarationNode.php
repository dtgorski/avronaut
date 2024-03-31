<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

use Avronaut\Tree\AstNode;
use Avronaut\Tree\Comments;
use Avronaut\Tree\Properties;

abstract class DeclarationNode extends AstNode
{
    private Comments $comments;

    /** @throws \Exception */
    public function __construct(?Properties $properties = null)
    {
        parent::__construct($properties);
        $this->comments = Comments::fromKeyValue([]);
    }

    /** @return Comments */
    public function getComments(): Comments
    {
        return $this->comments;
    }

    public function setComments(Comments $comments): DeclarationNode
    {
        $this->comments = $comments;
        return $this;
    }
}
