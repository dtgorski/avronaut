<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Type;

enum LogicalType: string
{
    case DATE = 'date';
    case TIME_MS = 'time_ms';
    case TIMESTAMP_MS = 'timestamp_ms';
    case LOCAL_TIMESTAMP_MS = 'local_timestamp_ms';
    case UUID = 'uuid';

    public static function hasType(string $type): bool
    {
        return in_array($type, self::values(), true);
    }

    /**
     * @codeCoverageIgnore Inherent static initialization does not play well with coverage.
     * @return string[] The logical type names.
     */
    public static function names(): array
    {
        return array_column(LogicalType::cases(), 'name');
    }

    /**
     * @codeCoverageIgnore Inherent static initialization does not play well with coverage.
     * @return string[] The logical type values.
     */
    public static function values(): array
    {
        return array_column(LogicalType::cases(), 'value');
    }
}
