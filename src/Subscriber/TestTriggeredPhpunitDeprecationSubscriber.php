<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\PhpunitDeprecationTriggered;
use PHPUnit\Event\Test\PhpunitDeprecationTriggeredSubscriber as PhpunitDeprecationTriggeredSubscriberInterface;
use PHPUnit\Status;

final class TestTriggeredPhpunitDeprecationSubscriber extends AbstractSubscriber implements PhpunitDeprecationTriggeredSubscriberInterface
{
    public function notify(PhpunitDeprecationTriggered $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Deprecation);
    }
}
