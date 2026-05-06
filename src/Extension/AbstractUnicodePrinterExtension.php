<?php

declare(strict_types=1);

namespace PHPUnit\Extension;

use PHPUnit\Printer\ProgressHandler;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\Subscriber\ConsideredRiskySubscriber;
use PHPUnit\Subscriber\ErroredSubscriber;
use PHPUnit\Subscriber\ExecutionFinishedSubscriber;
use PHPUnit\Subscriber\ExecutionStartedSubscriber;
use PHPUnit\Subscriber\FailedSubscriber;
use PHPUnit\Subscriber\FinishedSubscriber;
use PHPUnit\Subscriber\MarkedIncompleteSubscriber;
use PHPUnit\Subscriber\PassedSubscriber;
use PHPUnit\Subscriber\PreparedSubscriber;
use PHPUnit\Subscriber\SkippedSubscriber;
use PHPUnit\Subscriber\TestTriggeredDeprecationSubscriber;
use PHPUnit\Subscriber\TestTriggeredNoticeSubscriber;
use PHPUnit\Subscriber\TestTriggeredPhpDeprecationSubscriber;
use PHPUnit\Subscriber\TestTriggeredPhpNoticeSubscriber;
use PHPUnit\Subscriber\TestTriggeredPhpunitDeprecationSubscriber;
use PHPUnit\Subscriber\TestTriggeredPhpunitWarningSubscriber;
use PHPUnit\Subscriber\TestTriggeredPhpWarningSubscriber;
use PHPUnit\Subscriber\TestTriggeredWarningSubscriber;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\TextUI\Output\DefaultPrinter;
use PHPUnit\TextUI\Output\Printer;

abstract class AbstractUnicodePrinterExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        if ($configuration->noOutput()) {
            return;
        }

        $printer = $configuration->outputToStandardErrorStream()
            ? DefaultPrinter::standardError()
            : DefaultPrinter::standardOutput();

        $handler = $this->createHandler($printer, $configuration);

        $facade->registerSubscribers(
            new ExecutionStartedSubscriber($handler),
            new PreparedSubscriber($handler),
            new PassedSubscriber($handler),
            new FailedSubscriber($handler),
            new ErroredSubscriber($handler),
            new SkippedSubscriber($handler),
            new MarkedIncompleteSubscriber($handler),
            new ConsideredRiskySubscriber($handler),
            new TestTriggeredWarningSubscriber($handler),
            new TestTriggeredPhpWarningSubscriber($handler),
            new TestTriggeredPhpunitWarningSubscriber($handler),
            new TestTriggeredDeprecationSubscriber($handler),
            new TestTriggeredPhpDeprecationSubscriber($handler),
            new TestTriggeredPhpunitDeprecationSubscriber($handler),
            new TestTriggeredNoticeSubscriber($handler),
            new TestTriggeredPhpNoticeSubscriber($handler),
            new FinishedSubscriber($handler),
            new ExecutionFinishedSubscriber($handler),
        );
    }

    abstract protected function createHandler(Printer $printer, Configuration $configuration): ProgressHandler;
}
