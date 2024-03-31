<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Write;

class StandardWriter implements Writer
{
    public function write(string|float|int|null ...$args): void
    {
        foreach ($args as $arg) {
            echo (string)$arg;
        }
    }
}
