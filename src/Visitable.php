<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut;

interface Visitable
{
    /** @throws \Exception */
    public function accept(Visitor $visitor): void;
}
