<?php

declare(strict_types=1);

namespace PHPUnit\Printer;

use PHPUnit\Event\Code\Test;
use PHPUnit\Event\Telemetry\HRTime;
use PHPUnit\Status;

interface ProgressHandler
{
    public function executionStarted(int $totalTests): void;

    public function testPrepared(Test $test, HRTime $time): void;

    public function testStatus(Test $test, Status $status): void;

    public function testFinished(Test $test, HRTime $time): void;

    public function executionFinished(): void;
}
