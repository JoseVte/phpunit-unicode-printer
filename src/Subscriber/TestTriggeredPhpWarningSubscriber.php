<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\PhpWarningTriggered;
use PHPUnit\Event\Test\PhpWarningTriggeredSubscriber as PhpWarningTriggeredSubscriberInterface;
use PHPUnit\Status;

final class TestTriggeredPhpWarningSubscriber extends AbstractSubscriber implements PhpWarningTriggeredSubscriberInterface
{
    public function notify(PhpWarningTriggered $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Warning);
    }
}
