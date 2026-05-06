<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\PhpDeprecationTriggered;
use PHPUnit\Event\Test\PhpDeprecationTriggeredSubscriber as PhpDeprecationTriggeredSubscriberInterface;
use PHPUnit\Status;

final class TestTriggeredPhpDeprecationSubscriber extends AbstractSubscriber implements PhpDeprecationTriggeredSubscriberInterface
{
    public function notify(PhpDeprecationTriggered $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Deprecation);
    }
}
