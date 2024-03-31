<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut;

class AvroReference
{
    /** @throws \Exception */
    public static function fromString(string $reference): AvroReference
    {
        $parts = explode('.', $reference);

        $name = array_pop($parts);
        $namespace = join('.', $parts);

        return self::fromQualified(
            AvroNamespace::fromString($namespace),
            AvroName::fromString($name)
        );
    }

    public static function fromQualified(AvroNamespace $namespace, AvroName $name): AvroReference
    {
        return new self($namespace, $name);
    }

    private function __construct(
        private readonly AvroNamespace $namespace,
        private readonly AvroName $name
    ) {
    }

    public function getNamespace(): AvroNamespace
    {
        return $this->namespace;
    }

    public function getName(): AvroName
    {
        return $this->name;
    }

    public function getQualifiedName(): string
    {
        $namespace = $this->getNamespace()->getValue();
        $namespace .= $namespace !== '' ? '.' : '';

        return sprintf('%s%s', $namespace, $this->getName()->getValue());
    }
}
