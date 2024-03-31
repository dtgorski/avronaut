<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Tree;

class Comment
{
    public static function fromString(string $text): Comment
    {
        $text = preg_replace('/^(\s*)\/\*\*?/m', '', $text);
        $text = preg_replace('/\*\//m', '', $text);
        $text = trim($text);
        $text = preg_replace('/^\s+/m', '', $text);
        $text = preg_replace('/^\*\s/m', '', $text);

        return new self($text);
    }

    private function __construct(private readonly string $text)
    {
    }

    public function getValue(): string
    {
        return $this->text;
    }
}
