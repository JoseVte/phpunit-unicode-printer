<?php

declare(strict_types=1);

namespace PHPUnit\Printer;

use const PHP_EOL;
use const STR_PAD_LEFT;
use function max;
use function preg_replace;
use function round;
use function sprintf;
use function str_pad;
use function str_replace;
use function strlen;
use function ucfirst;
use PHPUnit\Event\Code\Test;
use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Telemetry\HRTime;
use PHPUnit\Status;
use PHPUnit\Symbols;
use PHPUnit\TextUI\Output\Printer;
use PHPUnit\Util\Color;

final class MethodPrinter implements ProgressHandler
{
    private int $totalTests = 0;
    private int $numTestsRun = 0;
    private int $numTestsWidth = 1;
    private ?HRTime $startedAt = null;
    private ?Status $currentStatus = null;

    public function __construct(
        private readonly Printer $printer,
        private readonly bool $colors,
    ) {
    }

    public function executionStarted(int $totalTests): void
    {
        $this->totalTests = $totalTests;
        $this->numTestsWidth = strlen((string) max($totalTests, 1));
        $this->printer->print(PHP_EOL);
    }

    public function testPrepared(Test $test, HRTime $time): void
    {
        $this->startedAt = $time;
        $this->currentStatus = Status::Passed;
    }

    public function testStatus(Test $test, Status $status): void
    {
        if ($this->currentStatus === null || $status->isMoreImportantThan($this->currentStatus)) {
            $this->currentStatus = $status;
        }
    }

    public function testFinished(Test $test, HRTime $time): void
    {
        $status = $this->currentStatus ?? Status::Passed;
        $durationMs = $this->startedAt !== null
            ? (int) round($time->duration($this->startedAt)->asFloat() * 1000)
            : 0;

        $this->numTestsRun++;

        $padding = str_pad((string) $this->numTestsRun, $this->numTestsWidth, ' ', STR_PAD_LEFT);
        $counter = sprintf('(%s/%d) ', $padding, $this->totalTests);

        $symbol = Symbols::symbol($status);
        $renderedSymbol = $this->colors
            ? Color::colorize(Symbols::color($status), $symbol)
            : $symbol;

        $row = $this->buildTestRow($test, $status, $durationMs);

        $this->printer->print($counter . $renderedSymbol . ' ' . $row . PHP_EOL);

        $this->currentStatus = null;
        $this->startedAt = null;
    }

    public function executionFinished(): void
    {
        $this->printer->print(PHP_EOL);
    }

    private function buildTestRow(Test $test, Status $status, int $durationMs): string
    {
        if ($test instanceof TestMethod) {
            $className = $test->className();
            $methodName = $test->methodName();
        } else {
            $className = '';
            $methodName = $test->name();
        }

        $colorTag = Symbols::color($status);

        $head = $className === ''
            ? ''
            : $this->colorize($colorTag, $className . ':') . ' ';

        $methodLabel = $this->colorize($colorTag . ',bold', $this->formatMethodName($methodName));

        return sprintf(
            '%s%s (%s)',
            $head,
            $methodLabel,
            $this->formatTestDuration($durationMs),
        );
    }

    private function colorize(string $colorTag, string $text): string
    {
        return $this->colors ? Color::colorize($colorTag, $text) : $text;
    }

    private function formatMethodName(string $method): string
    {
        $withSpaces = str_replace('_', ' ', $method);
        $split = (string) preg_replace('/(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/', ' ', $withSpaces);

        return ucfirst($split);
    }

    private function formatTestDuration(int $durationMs): string
    {
        $rendered = $this->colors && $durationMs > 500
            ? Color::colorize('fg-yellow', (string) $durationMs)
            : (string) $durationMs;

        return $rendered . ' ms';
    }
}
