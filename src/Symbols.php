<?php

declare(strict_types=1);

namespace PHPUnit;

final class Symbols
{
    private const CHECK   = "\xe2\x9c\x93"; // ✓
    private const CROSS   = "\xe2\x9c\x97"; // ✗
    private const WARN    = "\xe2\x9a\xa0"; // ⚠
    private const INFO    = "\xe2\x84\xb9"; // ℹ

    public static function symbol(Status $status): string
    {
        return match ($status) {
            Status::Passed,
            Status::Incomplete,
            Status::Risky => self::CHECK,
            Status::Failed,
            Status::Errored,
            Status::Warning,
            Status::Skipped => self::CROSS,
            Status::Deprecation => self::WARN,
            Status::Notice => self::INFO,
        };
    }

    public static function color(Status $status): string
    {
        return match ($status) {
            Status::Passed => 'fg-green,bold',
            Status::Failed => 'fg-red,bold',
            Status::Errored => 'fg-magenta,bold',
            Status::Warning,
            Status::Risky => 'fg-yellow,bold',
            Status::Deprecation => 'fg-yellow',
            Status::Notice => 'fg-cyan,bold',
            Status::Skipped,
            Status::Incomplete => 'fg-blue,bold',
        };
    }
}
