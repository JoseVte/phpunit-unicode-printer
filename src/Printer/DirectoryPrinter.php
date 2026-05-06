<?php

declare(strict_types=1);

namespace PHPUnit\Printer;

use function dirname;
use function getcwd;
use function preg_replace;
use function rtrim;
use function str_replace;
use function str_starts_with;
use function strlen;
use function substr;
use PHPUnit\Event\Code\Test;

final class DirectoryPrinter extends AbstractGroupedPrinter
{
    protected function labelWidthRatio(): float
    {
        return 0.4;
    }

    protected function computeGroupLabel(Test $test): string
    {
        $directory = $this->relativeDirectory($test->file());

        if ($directory === '') {
            return ' > Unit tests';
        }

        return ' > ' . str_replace('/', ' > ', $directory);
    }

    private function relativeDirectory(string $file): string
    {
        $directory = dirname($file);
        $cwd = rtrim((string) getcwd(), '/');

        if ($cwd !== '' && str_starts_with($directory, $cwd)) {
            $directory = substr($directory, strlen($cwd));
        }

        $directory = (string) preg_replace('#^/+#', '', $directory);
        $directory = (string) preg_replace('#^tests/?#', '', $directory);

        return $directory;
    }
}
