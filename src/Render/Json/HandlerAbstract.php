<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Json;

use Avronaut\Render\NodeHandler;
use Avronaut\Visitable;

/** @internal */
abstract class HandlerAbstract implements NodeHandler
{
    public function __construct(private readonly HandlerContext $context)
    {
    }

    public function getContext(): HandlerContext
    {
        return $this->context;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
    }

    protected function write(string|float|int|null ...$args): void
    {
        $this->getContext()->getWriter()->write(...$args);
    }
}
