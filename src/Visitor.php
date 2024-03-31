<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut;

interface Visitor
{
    /** @throws \Exception */
    public function visit(Visitable $node): bool;

    /** @throws \Exception */
    public function leave(Visitable $node): void;
}
