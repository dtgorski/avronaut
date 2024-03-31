<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Parse;

use Avronaut\Tree\AstNode;

/** @internal */
interface Parser
{
    /** @throws \Exception */
    public function parse(): AstNode;
}
