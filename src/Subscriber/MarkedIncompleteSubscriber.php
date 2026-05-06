<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Event\Test\MarkedIncomplete;
use PHPUnit\Event\Test\MarkedIncompleteSubscriber as MarkedIncompleteSubscriberInterface;
use PHPUnit\Status;

final class MarkedIncompleteSubscriber extends AbstractSubscriber implements MarkedIncompleteSubscriberInterface
{
    public function notify(MarkedIncomplete $event): void
    {
        $this->handler()->testStatus($event->test(), Status::Incomplete);
    }
}
