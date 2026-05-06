<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\ConsideredRisky;
use PHPUnit\Event\Test\ConsideredRiskySubscriber as ConsideredRiskySubscriberInterface;
use PHPUnit\Status;

final class ConsideredRiskySubscriber extends AbstractSubscriber implements ConsideredRiskySubscriberInterface
{
    public function notify(ConsideredRisky $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Risky);
    }
}
