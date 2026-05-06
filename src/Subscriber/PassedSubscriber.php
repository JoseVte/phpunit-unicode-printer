<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\Passed;
use PHPUnit\Event\Test\PassedSubscriber as PassedSubscriberInterface;
use PHPUnit\Status;

final class PassedSubscriber extends AbstractSubscriber implements PassedSubscriberInterface
{
    public function notify(Passed $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Passed);
    }
}
