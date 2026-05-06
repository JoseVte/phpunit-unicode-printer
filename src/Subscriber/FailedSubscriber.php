<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\FailedSubscriber as FailedSubscriberInterface;
use PHPUnit\Status;

final class FailedSubscriber extends AbstractSubscriber implements FailedSubscriberInterface
{
    public function notify(Failed $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Failed);
    }
}
