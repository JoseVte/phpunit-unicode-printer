<?php

declare(strict_types=1);

namespace PHPUnit\Extension;

use PHPUnit\Printer\MethodPrinter;
use PHPUnit\Printer\ProgressHandler;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\TextUI\Output\Printer;

final class UnicodePrinterMethod extends AbstractUnicodePrinterExtension
{
    protected function createHandler(Printer $printer, Configuration $configuration): ProgressHandler
    {
        return new MethodPrinter(
            $printer,
            $configuration->colors(),
        );
    }
}
