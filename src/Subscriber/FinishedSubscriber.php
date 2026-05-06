<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber as FinishedSubscriberInterface;

final class FinishedSubscriber extends AbstractSubscriber implements FinishedSubscriberInterface
{
    public function notify(Finished $event): void
    {
        $this->handler()->testFinished($event->test(), $event->telemetryInfo()->time());
    }
}
