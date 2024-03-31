<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tree;

abstract class AstNode extends TreeNode
{
    public function __construct(private ?Properties $properties = null)
    {
        if ($properties === null) {
            $this->properties = Properties::fromEmpty();
        }
        parent::__construct();
    }

    /** @psalm-suppress NullableReturnStatement, InvalidNullableReturnType */
    public function getProperties(): Properties
    {
        return $this->properties;
    }
}
