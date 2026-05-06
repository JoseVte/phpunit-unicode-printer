<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\Errored;
use PHPUnit\Event\Test\ErroredSubscriber as ErroredSubscriberInterface;
use PHPUnit\Status;

final class ErroredSubscriber extends AbstractSubscriber implements ErroredSubscriberInterface
{
    public function notify(Errored $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Errored);
    }
}
