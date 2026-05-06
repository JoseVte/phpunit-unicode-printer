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
use function strrchr;
use function substr;
use PHPUnit\Event\Code\Test;
use PHPUnit\Event\Code\TestMethod;

final class ClassPrinter extends AbstractGroupedPrinter
{
    protected function labelWidthRatio(): float
    {
        return 0.6;
    }

    protected function computeGroupLabel(Test $test): string
    {
        $directory = $this->relativeDirectory($test->file());
        $shortClass = $this->shortClassName($test);

        $base = $directory === '' ? ' > Unit tests' : ' > ' . str_replace('/', ' > ', $directory);

        return $base . ' > ' . $shortClass;
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

    private function shortClassName(Test $test): string
    {
        if ($test instanceof TestMethod) {
            $fqcn = $test->className();
            $tail = strrchr($fqcn, '\\');

            return $tail === false ? $fqcn : substr($tail, 1);
        }

        return $test->name();
    }
}
