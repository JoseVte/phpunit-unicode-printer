<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\Prepared;
use PHPUnit\Event\Test\PreparedSubscriber as PreparedSubscriberInterface;

final class PreparedSubscriber extends AbstractSubscriber implements PreparedSubscriberInterface
{
    public function notify(Prepared $event): void
    {
        $this->handler()->testPrepared($event->test(), $event->telemetryInfo()->time());
    }
}
