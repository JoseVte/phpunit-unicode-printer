<?php

declare(strict_types=1);

namespace PHPUnit\Extension;

use PHPUnit\Printer\ClassPrinter;
use PHPUnit\Printer\ProgressHandler;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\TextUI\Output\Printer;

final class UnicodePrinterClass extends AbstractUnicodePrinterExtension
{
    protected function createHandler(Printer $printer, Configuration $configuration): ProgressHandler
    {
        return new ClassPrinter(
            $printer,
            $configuration->colors(),
            $configuration->columns(),
        );
    }
}
