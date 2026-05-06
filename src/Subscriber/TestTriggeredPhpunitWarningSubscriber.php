<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\PhpunitWarningTriggered;
use PHPUnit\Event\Test\PhpunitWarningTriggeredSubscriber as PhpunitWarningTriggeredSubscriberInterface;
use PHPUnit\Status;

final class TestTriggeredPhpunitWarningSubscriber extends AbstractSubscriber implements PhpunitWarningTriggeredSubscriberInterface
{
    public function notify(PhpunitWarningTriggered $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Warning);
    }
}
