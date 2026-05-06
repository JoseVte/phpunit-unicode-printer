<?php

declare(strict_types=1);

namespace PHPUnit\Printer;

use const PHP_EOL;
use function floor;
use function max;
use function sprintf;
use function str_repeat;
use function strlen;
use function substr;
use PHPUnit\Event\Code\Test;
use PHPUnit\Event\Telemetry\HRTime;
use PHPUnit\Status;
use PHPUnit\Symbols;
use PHPUnit\TextUI\Output\Printer;
use PHPUnit\Util\Color;

abstract class AbstractGroupedPrinter implements ProgressHandler
{
    private const COUNT_SUFFIX_TEMPLATE = '  /  (XXX%)';

    protected string $groupLabel = '';
    protected string $lastGroupLabel = '';
    protected int $totalTests = 0;
    protected int $numTestsRun = 0;
    protected int $numTestsWidth = 1;
    protected int $maxSymbolsPerLine = 1;
    protected int $symbolsOnLine = 0;
    protected ?Status $currentStatus = null;
    protected readonly int $maxLabelLength;

    public function __construct(
        protected readonly Printer $printer,
        protected readonly bool $colors,
        protected readonly int $numberOfColumns,
    ) {
        $this->maxLabelLength = (int) ($numberOfColumns * $this->labelWidthRatio());
    }

    abstract protected function labelWidthRatio(): float;

    abstract protected function computeGroupLabel(Test $test): string;

    public function executionStarted(int $totalTests): void
    {
        $this->totalTests = $totalTests;
        $this->numTestsWidth = strlen((string) max($totalTests, 1));
        $this->maxSymbolsPerLine = max(
            1,
            $this->numberOfColumns - $this->maxLabelLength - strlen(self::COUNT_SUFFIX_TEMPLATE) - 2 * $this->numTestsWidth,
        );
    }

    public function testPrepared(Test $test, HRTime $time): void
    {
        $this->groupLabel = $this->computeGroupLabel($test);
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
        $this->ensureGroupHeaderWritten();
        $this->writeSymbol($status);
        $this->numTestsRun++;
        $this->symbolsOnLine++;

        if ($this->symbolsOnLine >= $this->maxSymbolsPerLine || $this->numTestsRun === $this->totalTests) {
            $this->wrapLine(true);
        }

        $this->currentStatus = null;
    }

    public function executionFinished(): void
    {
        if ($this->symbolsOnLine > 0) {
            $this->wrapLine(false);
        }
    }

    private function ensureGroupHeaderWritten(): void
    {
        if ($this->groupLabel === $this->lastGroupLabel) {
            return;
        }

        if ($this->numTestsRun > 0) {
            $this->wrapLine(false);
        } else {
            $this->printer->print(PHP_EOL);
        }

        $name = $this->truncateLabel($this->groupLabel);
        $this->printer->print($this->colors ? Color::colorize('fg-cyan,bold', $name) : $name);
        $this->printer->print(str_repeat(' ', max($this->maxLabelLength - strlen($name), 0)));

        $this->lastGroupLabel = $this->groupLabel;
    }

    private function writeSymbol(Status $status): void
    {
        $symbol = Symbols::symbol($status);
        $this->printer->print($this->colors ? Color::colorize(Symbols::color($status), $symbol) : $symbol);
    }

    private function wrapLine(bool $continueWithSamePadding): void
    {
        $padding = max(0, $this->maxSymbolsPerLine - $this->symbolsOnLine);
        $this->printer->print(str_repeat(' ', $padding) . ' ' . $this->renderProgressCount() . PHP_EOL);

        $this->symbolsOnLine = 0;

        if ($continueWithSamePadding && $this->numTestsRun < $this->totalTests) {
            $this->printer->print(str_repeat(' ', $this->maxLabelLength));
        }
    }

    private function renderProgressCount(): string
    {
        $percent = $this->totalTests > 0
            ? (int) floor(($this->numTestsRun / $this->totalTests) * 100)
            : 0;

        return sprintf(
            '%' . $this->numTestsWidth . 'd/%' . $this->numTestsWidth . 'd (%3d%%)',
            $this->numTestsRun,
            $this->totalTests,
            $percent,
        );
    }

    private function truncateLabel(string $label): string
    {
        if (strlen($label) <= $this->maxLabelLength) {
            return $label;
        }

        $keep = $this->maxLabelLength - 3;

        return '...' . substr($label, strlen($label) - $keep);
    }
}
