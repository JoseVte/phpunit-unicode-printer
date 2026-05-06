<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\Skipped;
use PHPUnit\Event\Test\SkippedSubscriber as SkippedSubscriberInterface;
use PHPUnit\Status;

final class SkippedSubscriber extends AbstractSubscriber implements SkippedSubscriberInterface
{
    public function notify(Skipped $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Skipped);
    }
}
