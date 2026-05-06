<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\DeprecationTriggered;
use PHPUnit\Event\Test\DeprecationTriggeredSubscriber as DeprecationTriggeredSubscriberInterface;
use PHPUnit\Status;

final class TestTriggeredDeprecationSubscriber extends AbstractSubscriber implements DeprecationTriggeredSubscriberInterface
{
    public function notify(DeprecationTriggered $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Deprecation);
    }
}
