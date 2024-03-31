<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Type;

enum ErrorType: string
{
    case THROWS = 'throws';
    case ONEWAY = 'oneway';

    public static function hasType(string $type): bool
    {
        return in_array($type, self::values(), true);
    }

    /**
     * @codeCoverageIgnore Inherent static initialization does not play well with coverage.
     * @return string[] The error type names.
     */
    public static function names(): array
    {
        return array_column(ErrorType::cases(), 'name');
    }

    /**
     * @codeCoverageIgnore Inherent static initialization does not play well with coverage.
     * @return string[] The error type values.
     */
    public static function values(): array
    {
        return array_column(ErrorType::cases(), 'value');
    }
}
