<?php

namespace PHPUnit;

use Throwable;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\Warning;
use PHPUnit\Util\Test as UtilTest;
use PHPUnit\TextUI\DefaultResultPrinter;
use PHPUnit\Framework\AssertionFailedError;

/**
 * Class PrinterMethod.
 *
 * @license MIT
 */
class PrinterMethod extends DefaultResultPrinter
{
    /**
     * Structure of the outputted test row.
     *
     * @var string
     */
    protected $testRow = '';

    /** @var string */
    protected $errorEmoji = "\xe2\x9c\x97"; // '✘' yellow

    /** @var string */
    protected $failureEmoji = "\xe2\x9c\x97"; // '✘' red

    /** @var string */
    protected $incompleteEmoji = "\xe2\x9c\x93"; // '✔' blue

    /** @var string */
    protected $riskyEmoji = "\xe2\x9c\x93"; // '✔' yellow

    /** @var string */
    protected $skippedEmoji = "\xe2\x9c\x97"; // '✘' blue

    /** @var string */
    protected $passEmoji = "\xe2\x9c\x93"; // '✔' green

    /**
     * {@inheritdoc}
     */
    protected function writeProgress(string $progress): void
    {
        ++$this->numTestsRun;
        $padding = str_pad($this->numTestsRun, strlen($this->numTests), ' ', STR_PAD_LEFT);

        $this->write("({$padding}/{$this->numTests}) ");
        if ($this->hasReplacementSymbol($progress)) {
            $color = $this->getColor($progress);
            $progress = $this->getSymbol($progress);
            $this->write($this->colorizeTextBox($color.',bold', $progress));
        } else {
            $this->write($progress);
        }
        $this->write((string) ' '.$this->testRow.PHP_EOL);
    }

    /**
     * {@inheritdoc}
     */
    public function addError(Test $test, Throwable $t, float $time): void
    {
        $this->buildTestRow(get_class($test), $test->getName(), $time, $this->getColor('E'));

        parent::addError($test, $t, $time);
    }

    /**
     * {@inheritdoc}
     */
    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        $this->buildTestRow(get_class($test), $test->getName(), $time, $this->getColor('F'));

        parent::addFailure($test, $e, $time);
    }

    /**
     * {@inheritdoc}
     */
    public function addWarning(Test $test, Warning $e, float $time): void
    {
        $this->buildTestRow(get_class($test), $test->getName(), $time, $this->getColor('W'));

        parent::addWarning($test, $e, $time);
    }

    /**
     * {@inheritdoc}
     */
    public function addIncompleteTest(Test $test, Throwable $t, float $time): void
    {
        $this->buildTestRow(get_class($test), $test->getName(), $time, $this->getColor('I'));

        parent::addIncompleteTest($test, $t, $time);
    }

    /**
     * {@inheritdoc}
     */
    public function addRiskyTest(Test $test, Throwable $t, float $time): void
    {
        $this->buildTestRow(get_class($test), $test->getName(), $time, $this->getColor('R'));

        parent::addRiskyTest($test, $t, $time);
    }

    /**
     * {@inheritdoc}
     */
    public function addSkippedTest(Test $test, Throwable $t, float $time): void
    {
        $this->buildTestRow(get_class($test), $test->getName(), $time, $this->getColor('S'));

        parent::addSkippedTest($test, $t, $time);
    }

    /**
     * {@inheritdoc}
     */
    public function endTest(Test $test, float $time): void
    {
        $testName = UtilTest::describeAsString($test);

        [$className, $methodName] = explode('::', $testName);

        $this->buildTestRow($className, $methodName, $time);

        parent::endTest($test, $time);
    }

    /**
     * {@inheritdoc}
     *
     * We'll handle the coloring ourselves.
     */
    protected function writeProgressWithColor(string $color, string $buffer): void
    {
        $this->writeProgress($buffer);
    }

    /**
     * Formats the results for a single test.
     */
    protected function buildTestRow(string $className, string $methodName, float $time, string $color = null): void
    {
        $color = $color ?: $this->getColor('.');

        $this->testRow = sprintf(
            '%s %s%s (%s)',
            $this->colorizeTextBox($color, "{$className}:"),
            $this->colorizeTextBox($color.',bold', $this->formatMethodName($methodName)),
            $this->verbose ? ' ['.$methodName.']' : '',
            $this->formatTestDuration($time)
        );
    }

    /**
     * Makes the method name more readable.
     */
    protected function formatMethodName(string $method): string
    {
        return ucfirst(
            $this->splitCamels(
                $this->splitSnakes($method)
            )
        );
    }

    /**
     * Replaces underscores in snake case with spaces.
     */
    protected function splitSnakes(string $name): string
    {
        return str_replace('_', ' ', $name);
    }

    /**
     * Splits camel-cased names while handling caps sections properly.
     */
    protected function splitCamels(string $name): string
    {
        return preg_replace('/(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/', ' $1', $name);
    }

    /**
     * Colours the duration if the test took longer than 500ms.
     */
    protected function formatTestDuration(float $time): string
    {
        $testDurationInMs = round($time * 1000);

        $duration = $testDurationInMs > 500
            ? $this->colorizeTextBox('fg-yellow', $testDurationInMs)
            : $testDurationInMs;

        return sprintf('%s ms', $duration);
    }

    /**
     * Verifies if we have a replacement symbol available.
     */
    protected function hasReplacementSymbol(string $progress): bool
    {
        $progressSymbol = $this->getSymbol($progress);

        return $this->colors && $progressSymbol !== $progress;
    }

    /**
     * Get the symbol from the PHPUnit status.
     */
    protected function getSymbol(string $progress): string
    {
        switch (strtoupper($progress)) {
            case 'W':
            case 'E':
                $progress = $this->errorEmoji;
                break;
            case 'F':
                $progress = $this->failureEmoji;
                break;
            case 'I':
                $progress = $this->incompleteEmoji;
                break;
            case 'R':
                $progress = $this->riskyEmoji;
                break;
            case 'S':
                $progress = $this->skippedEmoji;
                break;
            case '.':
                $progress = $this->passEmoji;
                break;
            default:
        }

        return $progress;
    }

    /**
     * Get the color from the PHPUnit status.
     */
    protected function getColor(string $progress): string
    {
        switch (strtoupper($progress)) {
            case 'E':
                $color = 'fg-magenta';
                break;
            case 'R':
            case 'W':
                $color = 'fg-yellow';
                break;
            case 'F':
                $color = 'fg-red';
                break;
            case 'S':
            case 'I':
                $color = 'fg-blue';
                break;
            case '.':
                $color = 'fg-green';
                break;
            default:
                $color = 'fg-white';
        }

        return $color;
    }
}
