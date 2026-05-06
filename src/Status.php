<?php

declare(strict_types=1);

namespace PHPUnit;

enum Status: string
{
    case Passed = 'passed';
    case Failed = 'failed';
    case Errored = 'errored';
    case Skipped = 'skipped';
    case Incomplete = 'incomplete';
    case Risky = 'risky';
    case Warning = 'warning';
    case Deprecation = 'deprecation';
    case Notice = 'notice';

    public function priority(): int
    {
        return match ($this) {
            self::Errored => 8,
            self::Failed => 7,
            self::Warning => 6,
            self::Risky => 5,
            self::Deprecation => 4,
            self::Notice => 3,
            self::Incomplete => 2,
            self::Skipped => 1,
            self::Passed => 0,
        };
    }

    public function isMoreImportantThan(self $other): bool
    {
        return $this->priority() > $other->priority();
    }
}
