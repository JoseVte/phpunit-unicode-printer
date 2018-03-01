<?php

namespace PHPUnit;

use ReflectionClass;
use PHPUnit_Framework_Test;
use PHPUnit_TextUI_ResultPrinter;
use PHPUnit_Framework_TestListener;
use SebastianBergmann\Environment\Console;

/**
 * Class Printer.
 *
 * @license MIT
 */
class PrinterClass extends PHPUnit_TextUI_ResultPrinter implements PHPUnit_Framework_TestListener
{
    use FormatOutput;

    /** @var string */
    protected $className = '';

    /** @var string */
    protected $lastClassName = '';

    /** @var int */
    protected $maxClassNameLength;

    /** @var int */
    protected $maxNumberOfColumns;

    /** @var int */
    protected $spaceAfter = 0;

    /** @var int */
    protected $sizeUnicode = 3;

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
        $this->maxClassNameLength = (int)($numberOfColumns * 0.6);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \ReflectionException
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        $class = new ReflectionClass(get_class($test));
        $remove = str_replace(array('/vendor/josrom', '/phpunit-unicode-printer/src'), '', dirname(__FILE__));
        $class = str_replace(array($remove, '/tests'), '', dirname($class->getFileName()));
        $this->className = empty($class) ? ' > Unit tests' : str_replace('/', ' > ', $class);
        $this->className .= ' > '.get_class($test);
        parent::startTest($test);
    }
}
