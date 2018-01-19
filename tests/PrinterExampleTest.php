<?php

class PrinterExampleTest extends TestCase
{
    /**
     * Default preparation for each test.
     */
    public function setUp()
    {
        parent::setUp();
    }

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
    }

    /**
     * @test
     */
    public function it_checks_test_incomplete()
    {
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
        $this->getMockWithoutInvokingTheOriginalConstructor('BasketRepository');
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
