<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\WarningTriggered;
use PHPUnit\Event\Test\WarningTriggeredSubscriber as WarningTriggeredSubscriberInterface;
use PHPUnit\Status;

final class TestTriggeredWarningSubscriber extends AbstractSubscriber implements WarningTriggeredSubscriberInterface
{
    public function notify(WarningTriggered $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Warning);
    }
}
