<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\TestRunner\ExecutionStarted;
use PHPUnit\Event\TestRunner\ExecutionStartedSubscriber as ExecutionStartedSubscriberInterface;

final class ExecutionStartedSubscriber extends AbstractSubscriber implements ExecutionStartedSubscriberInterface
{
    public function notify(ExecutionStarted $event): void
    {
        $this->handler()->executionStarted($event->testSuite()->count());
    }
}
