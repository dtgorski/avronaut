<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Render\Json;

use Avronaut\Write\Writer;

/** @internal */
class HandlerContext
{
    public function __construct(private readonly Writer $writer)
    {
    }

    public function getWriter(): Writer
    {
        return $this->writer;
    }
}
