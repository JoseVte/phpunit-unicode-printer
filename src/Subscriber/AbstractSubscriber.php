<?php

declare(strict_types=1);

namespace PHPUnit\Subscriber;

use PHPUnit\Printer\ProgressHandler;

abstract class AbstractSubscriber
{
    public function __construct(private readonly ProgressHandler $handler)
    {
    }

    protected function handler(): ProgressHandler
    {
        return $this->handler;
    }
}
