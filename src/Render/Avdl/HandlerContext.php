<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Avdl;

use Avronaut\Write\Writer;

/** @internal */
class HandlerContext
{
    private int $depth = 0;

    public function __construct(private readonly Writer $writer)
    {
    }

    public function getWriter(): Writer
    {
        return $this->writer;
    }

    public function stepIn(): void
    {
        $this->depth++;
    }

    public function stepOut(): void
    {
        if (--$this->depth < 0) {
            throw new \RuntimeException('step underrun');
        }
    }

    public function getDepth(): int
    {
        return $this->depth;
    }
}
