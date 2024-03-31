<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

use Avronaut\Tree\AstNode;
use Avronaut\Type\ImportType;

class ImportStatementNode extends AstNode
{
    public function __construct(
        private readonly ImportType $type,
        private readonly string $path,
    ) {
        parent::__construct();
    }

    public function getType(): ImportType
    {
        return $this->type;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
