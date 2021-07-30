<?php

use PHPUnit\Framework\TestCase;

class PrinterExampleTest extends TestCase
{
    /**
     * @test
     */
    public function it_checks_test_works(): void
    {
        self::assertEquals(2, 2);
    }

    /**
     * @test
     */
    public function it_checks_test_risky(): void
    {
        $this->markAsRisky();
    }

    /**
     * @test
     */
    public function it_checks_test_incomplete(): void
    {
        self::assertEquals(2, 2);
        self::markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @test
     */
    public function it_checks_test_fail(): void
    {
        self::fail('Test fail');
    }

    /**
     * @test
     */
    public function it_checks_test_warning(): void
    {
        $this->addWarning('BasketRepository not exists');
    }

    /**
     * @test
     */
    public function it_checks_test_error(): void
    {
        fopen();
    }

    /**
     * @test
     */
    public function it_checks_test_skipped(): void
    {
        self::markTestSkipped('This test has been skipped.');
    }
}
