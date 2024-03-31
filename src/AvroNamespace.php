<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut;

class AvroNamespace
{
    /** @var string[] $path */
    private readonly array $path;

    /** @throws \Exception */
    public static function fromString(string $namespace): AvroNamespace
    {
        return new self($namespace);
    }

    /** @throws \Exception */
    private function __construct(private readonly string $namespace)
    {
        if ($namespace !== '') {
            if (!preg_match(self::REGEX, $namespace)) {
                throw new \Exception(sprintf("unexpected invalid namespace '%s'", $namespace));
            }
        }
        $this->path = explode('.', $namespace);
    }

    /** @return string[] */
    public function getPath(): array
    {
        return $this->path;
    }

    public function getValue(): string
    {
        return $this->namespace;
    }

    public function isEmpty(): bool
    {
        return $this->getValue() == '';
    }

    public function equals(AvroNamespace $namespace): bool
    {
        return $this->getValue() == $namespace->getValue();
    }

    private const REGEX = '/^[a-z_][a-z0-9_]*(\.[a-z_][a-z0-9_]*)*$/i';
}
