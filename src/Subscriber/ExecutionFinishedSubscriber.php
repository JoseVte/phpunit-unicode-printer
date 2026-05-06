<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\TestRunner\ExecutionFinished;
use PHPUnit\Event\TestRunner\ExecutionFinishedSubscriber as ExecutionFinishedSubscriberInterface;

final class ExecutionFinishedSubscriber extends AbstractSubscriber implements ExecutionFinishedSubscriberInterface
{
    public function notify(ExecutionFinished $event): void
    {
        $this->handler()->executionFinished();
    }
}
