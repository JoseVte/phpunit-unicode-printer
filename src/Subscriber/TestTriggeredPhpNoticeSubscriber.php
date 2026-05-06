<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\PhpNoticeTriggered;
use PHPUnit\Event\Test\PhpNoticeTriggeredSubscriber as PhpNoticeTriggeredSubscriberInterface;
use PHPUnit\Status;

final class TestTriggeredPhpNoticeSubscriber extends AbstractSubscriber implements PhpNoticeTriggeredSubscriberInterface
{
    public function notify(PhpNoticeTriggered $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Notice);
    }
}
