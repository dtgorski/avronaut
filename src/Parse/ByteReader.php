<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Parse;

/** @internal */
interface ByteReader
{
    public function readByte(): int;

    public function getLine(): int;

    public function getColumn(): int;
}
