<?php

namespace PHPUnit;

use ReflectionClass;
use PHPUnit\Framework\Test;
use PHPUnit\TextUI\ResultPrinter;
use SebastianBergmann\Environment\Console;

/**
 * Class Printer.
 *
 * @license MIT
 */
class Printer extends ResultPrinter
{
    /** @var string */
    private $className = '';

    /** @var string */
    private $lastClassName = '';

    /** @var int */
    private $maxClassNameLength;

    /** @var int */
    private $maxNumberOfColumns;

    /** @var int */
    private $spaceAfter = 0;

    /** @var int */
    private $sizeUnicode = 3;

    /** @var string */
    private $errorEmoji = "\xe2\x9c\x97"; // '✘' yellow

    /** @var string */
    private $failureEmoji = "\xe2\x9c\x97"; // '✘' red

    /** @var string */
    private $incompleteEmoji = "\xe2\x9c\x93"; // '✔' blue

    /** @var string */
    private $riskyEmoji = "\xe2\x9c\x93"; // '✔' yellow

    /** @var string */
    private $skippedEmoji = "\xe2\x9c\x97"; // '✘' blue

    /** @var string */
    private $passEmoji = "\xe2\x9c\x93"; // '✔' green

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $out = null,
        $verbose = false,
        $colors = self::COLOR_DEFAULT,
        $debug = false,
        $numberOfColumns = 80
    ) {
        parent::__construct($out, $verbose, $colors, $debug, $numberOfColumns);

        if ($numberOfColumns === 'max') {
            $console = new Console();
            $numberOfColumns = $console->getNumberOfColumns();
        }
        $this->maxNumberOfColumns = $numberOfColumns;
        $this->maxClassNameLength = (int) ($numberOfColumns * 0.4);
    }

    /**
     * {@inheritdoc}
     */
    protected function writeProgress($progress):void
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
    protected function writeProgressWithColor($color, $buffer):void
    {
        if ($this->debug) {
            parent::writeProgressWithColor($color, $buffer);
        }

        $this->printClassName();
        $this->printTestCaseStatus($color, $buffer);
        $this->printTestsStatus();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \ReflectionException
     */
    public function startTest(Test $test) : void
    {
        $class = new ReflectionClass(get_class($test));
        $remove = str_replace(array('/vendor/josrom', '/phpunit-unicode-printer/src'), '', dirname(__FILE__));
        $class = str_replace(array($remove, '/tests'), '', dirname($class->getFileName()));
        $this->className = empty($class) ? ' > Unit tests' : str_replace('/', ' > ', $class);
        parent::startTest($test);
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
    private function printTestCaseStatus($color, $buffer)
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

        $this->write(parent::formatWithColor($color, $buffer));
        $this->spaceAfter += strlen($buffer);
        ++$this->column;
        ++$this->numTestsRun;
    }

    /**
     * Print the status of tests at end of line.
     */
    private function printTestsStatus()
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
    private function formatClassName($className)
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
    private function isCIEnvironment()
    {
        return isset($_SERVER['PHP_CI']) && $_SERVER['PHP_CI'] === 'true';
    }
}
