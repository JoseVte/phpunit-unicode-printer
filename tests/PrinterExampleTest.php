<?php

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PrinterExampleTest extends TestCase
{
    #[Test]
    public function it_checks_test_works(): void
    {
        self::assertEquals(2, 2);
    }

    #[Test]
    public function it_checks_test_risky(): void
    {
        // No assertion: marked risky by `beStrictAboutTestsThatDoNotTestAnything`.
    }

    #[Test]
    public function it_checks_test_incomplete(): void
    {
        self::assertEquals(2, 2);
        self::markTestIncomplete('This test has not been implemented yet.');
    }

    #[Test]
    public function it_checks_test_fail(): void
    {
        self::fail('Test fail');
    }

    #[Test]
    public function it_checks_test_warning(): void
    {
        trigger_error('MyWarning', E_USER_WARNING);
        self::assertEquals(2, 2);
    }

    #[Test]
    public function it_checks_test_error(): void
    {
        fopen();
    }

    #[Test]
    public function it_checks_test_skipped(): void
    {
        self::markTestSkipped('This test has been skipped.');
    }

    #[Test]
    public function it_checks_test_deprecation(): void
    {
        trigger_error('MyDeprecation', E_USER_DEPRECATED);
        self::assertEquals(2, 2);
    }

    #[Test]
    public function it_checks_test_notice(): void
    {
        trigger_error('MyNotice', E_USER_NOTICE);
        self::assertEquals(2, 2);
    }
}
