<?php

namespace PHPUnit;

use PHPUnit\Util\Filter;
use PHPUnit\Framework\TestFailure;

trait FormatOutput
{
    /**
     * {@inheritdoc}
     */
    protected function writeProgress(string $progress): void
    {
        if ($this->debug) {
            parent::writeProgress($progress);

            return;
        }

        $this->printClassName();
        $this->printTestCaseStatus('', $progress);
        $this->printTestsStatus();
    }

    /**
     * {@inheritdoc}
     */
    protected function writeProgressWithColor(string $color, string $buffer): void
    {
        if ($this->debug) {
            parent::writeProgressWithColor($color, $buffer);
        }

        $this->printClassName();
        $this->printTestCaseStatus($color, $buffer);
        $this->printTestsStatus();
    }

    /**
     * Prints the Class Name if it has changed.
     */
    protected function printClassName()
    {
        if ($this->lastClassName === $this->className) {
            return;
        }

        if ($this->numTestsRun > 0) {
            $this->write(str_repeat(' ', $this->maxNumberOfColumns - ($this->spaceAfter / $this->sizeUnicode)));
            $this->spaceAfter = 0;
            $this->write(sprintf('%'.$this->numTestsWidth.'d/%'.$this->numTestsWidth.'d (%3s%%)', $this->numTestsRun, $this->numTests, floor(($this->numTestsRun / $this->numTests) * 100)));
        }

        echo PHP_EOL;
        $className = $this->formatClassName($this->className);
        if ($this->colors === true) {
            $this->writeWithColor('fg-cyan,bold', $className, false);
        } else {
            $this->write($className);
        }
        $this->column = strlen($className);
        $this->write(str_repeat(' ', max($this->maxClassNameLength - $this->column, 0)));

        $this->lastClassName = $this->className;
    }

    /**
     * @param string $color
     * @param string $buffer Result of the Test Case => . F S I R
     */
    protected function printTestCaseStatus($color, $buffer)
    {
        if ($this->isCIEnvironment()) {
            echo $buffer;
            ++$this->column;
            ++$this->numTestsRun;

            return;
        }

        switch (strtoupper($buffer)) {
            case 'E':
                $color = 'fg-magenta,bold';
                $buffer = $this->errorEmoji;
                break;
            case 'W':
                $color = 'fg-yellow,bold';
                $buffer = $this->errorEmoji;
                break;
            case 'F':
                $color = 'fg-red,bold';
                $buffer = $this->failureEmoji;
                break;
            case 'I':
                $color = 'fg-blue,bold';
                $buffer = $this->incompleteEmoji;
                break;
            case 'R':
                $color = 'fg-yellow,bold';
                $buffer = $this->riskyEmoji;
                break;
            case 'S':
                $color = 'fg-blue,bold';
                $buffer = $this->skippedEmoji;
                break;
            case '.':
                $color = 'fg-green,bold';
                $buffer = $this->passEmoji;
                break;
        }

        $this->write(parent::colorizeTextBox($color, $buffer));
        $this->spaceAfter += strlen($buffer);
        ++$this->column;
        ++$this->numTestsRun;
    }

    /**
     * Print the status of tests at end of line.
     */
    protected function printTestsStatus()
    {
        if ((($this->spaceAfter / $this->sizeUnicode) >= ($this->maxNumberOfColumns - $this->sizeUnicode) && $this->numTestsRun <= $this->numTests) || $this->numTestsRun == $this->numTests) {
            $this->write(str_repeat(' ', $this->maxNumberOfColumns - ($this->spaceAfter / $this->sizeUnicode)));
            $this->spaceAfter = 0;

            $this->write(sprintf('%'.$this->numTestsWidth.'d/%'.$this->numTestsWidth.'d (%3s%%)', $this->numTestsRun, $this->numTests, floor(($this->numTestsRun / $this->numTests) * 100)));
            $this->writeNewLine();
            $padding = $this->maxClassNameLength;
            $this->column = $padding;
            $this->spaceAfter = 0;
            $this->write(str_repeat(' ', $padding));
        }
    }

    /**
     * Limit the class name.
     *
     * @param string $className
     *
     * @return string
     */
    protected function formatClassName($className)
    {
        if (strlen($className) <= $this->maxClassNameLength) {
            return $className;
        }

        return '...'.substr($className, strlen($className) - $this->maxClassNameLength + 3, $this->maxClassNameLength);
    }

    /**
     * Detects if PHPUnit is executed in a CI Environment - in this case the UTF-8 Symbols are
     * deactivated because they are not correct displayed in the report.
     *
     * At the moment only travis is support and when its manually disabled
     *
     * @return bool
     */
    protected function isCIEnvironment()
    {
        return isset($_SERVER['PHP_CI']) && $_SERVER['PHP_CI'] === 'true';
    }

    /**
     * {@inheritdoc}
     * @throws \ReflectionException
     */
    protected function printDefectTrace(TestFailure $defect) : void
    {
        $this->write($this->formatExceptionMsg($defect->getExceptionAsString()));
        $trace = Filter::getFilteredStacktrace(
            $defect->thrownException()
        );
        if (!empty($trace)) {
            $this->write("\n".$trace);
        }
        $exception = $defect->thrownException()->getPrevious();
        while ($exception) {
            $this->write(
                "\nCaused by\n".
                TestFailure::exceptionToString($exception)."\n".
                Filter::getFilteredStacktrace($exception)
            );
            $exception = $exception->getPrevious();
        }
    }

    /**
     * Format the exception message.
     *
     * @param $exceptionMessage
     *
     * @return mixed|null|string|string[]
     */
    protected function formatExceptionMsg($exceptionMessage)
    {
        $exceptionMessage = str_replace("+++ Actual\n", '', $exceptionMessage);
        $exceptionMessage = str_replace("--- Expected\n", '', $exceptionMessage);
        $exceptionMessage = str_replace('@@ @@', '', $exceptionMessage);
        if ($this->colors) {
            $exceptionMessage = preg_replace('/^(Exception.*)$/m', "\033[01;31m$1\033[0m", $exceptionMessage);
            $exceptionMessage = preg_replace('/(Failed.*)$/m', "\033[01;31m$1\033[0m", $exceptionMessage);
            $exceptionMessage = preg_replace("/(\-+.*)$/m", "\033[01;32m$1\033[0m", $exceptionMessage);
            $exceptionMessage = preg_replace("/(\++.*)$/m", "\033[01;31m$1\033[0m", $exceptionMessage);
        }

        return $exceptionMessage;
    }
}
