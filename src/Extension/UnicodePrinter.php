<?php

declare(strict_types=1);

namespace PHPUnit\Extension;

use PHPUnit\Printer\DirectoryPrinter;
use PHPUnit\Printer\ProgressHandler;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\TextUI\Output\Printer;

final class UnicodePrinter extends AbstractUnicodePrinterExtension
{
    protected function createHandler(Printer $printer, Configuration $configuration): ProgressHandler
    {
        return new DirectoryPrinter(
            $printer,
            $configuration->colors(),
            $configuration->columns(),
        );
    }
}
