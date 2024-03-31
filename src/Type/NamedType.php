<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Type;

enum NamedType: string
{
    case ENUM = 'enum';
    case ERROR = 'error';
    case FIXED = 'fixed';
    case RECORD = 'record';

    public static function hasType(string $type): bool
    {
        return in_array($type, self::values(), true);
    }

    /**
     * @codeCoverageIgnore Inherent static initialization does not play well with coverage.
     * @return string[] The named type names.
     */
    public static function names(): array
    {
        return array_column(NamedType::cases(), 'name');
    }

    /**
     * @codeCoverageIgnore Inherent static initialization does not play well with coverage.
     * @return string[] The named type values.
     */
    public static function values(): array
    {
        return array_column(NamedType::cases(), 'value');
    }
}
