<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Type;

enum PrimitiveType: string
{
    case BOOLEAN = 'boolean';
    case BYTES = 'bytes';
    case INT = 'int';
    case STRING = 'string';
    case FLOAT = 'float';
    case DOUBLE = 'double';
    case LONG = 'long';
    case NULL = 'null';

    public static function hasType(string $type): bool
    {
        return in_array($type, self::values(), true);
    }

    /**
     * @codeCoverageIgnore Inherent static initialization does not play well with coverage.
     * @return string[] The primitive type names.
     */
    public static function names(): array
    {
        return array_column(PrimitiveType::cases(), 'name');
    }

    /**
     * @codeCoverageIgnore Inherent static initialization does not play well with coverage.
     * @return string[] The primitive type values.
     */
    public static function values(): array
    {
        return array_column(PrimitiveType::cases(), 'value');
    }
}
