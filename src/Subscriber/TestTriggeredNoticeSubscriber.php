<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\NoticeTriggered;
use PHPUnit\Event\Test\NoticeTriggeredSubscriber as NoticeTriggeredSubscriberInterface;
use PHPUnit\Status;

final class TestTriggeredNoticeSubscriber extends AbstractSubscriber implements NoticeTriggeredSubscriberInterface
{
    public function notify(NoticeTriggered $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Notice);
    }
}
