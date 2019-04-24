<?php

use PHPUnit\Framework\TestCase;

class PrinterExampleTest extends TestCase
{
    /**
     * @test
     */
    public function it_checks_test_works()
    {
        $this->assertEquals(2, 2);
    }

    /**
     * @test
     */
    public function it_checks_test_risky()
    {
        $this->markAsRisky();
        $this->assertEquals(2, 2);
    }

    /**
     * @test
     */
    public function it_checks_test_incomplete()
    {
        $this->assertEquals(2, 2);
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @test
     */
    public function it_checks_test_fail()
    {
        $this->fail('Test fail');
    }

    /**
     * @test
     */
    public function it_checks_test_warning()
    {
        $this->addWarning('BasketRepository not exists');
    }

    /**
     * @test
     */
    public function it_checks_test_error()
    {
        fopen();
    }

    /**
     * @test
     */
    public function it_checks_test_skipped()
    {
        $this->markTestSkipped('This test has been skipped.');
    }
}
