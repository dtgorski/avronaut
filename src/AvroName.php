<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut;

class AvroName
{
    /** @throws \Exception */
    public static function fromString(string $name): AvroName
    {
        return new self($name);
    }

    /** @throws \Exception */
    private function __construct(private readonly string $name)
    {
        if (!preg_match(self::REGEX, $name)) {
            throw new \Exception(sprintf("unexpected invalid name '%s'", $name));
        }
    }

    public function getValue(): string
    {
        return $this->name;
    }

    public function equals(AvroName $name): bool
    {
        return $this->getValue() == $name->getValue();
    }

    private const REGEX = '/^[$a-z_][$a-z0-9_]*$/i';
}
