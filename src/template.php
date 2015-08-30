<?php echo '<?php' . PHP_EOL ?>
use <?php echo $originalClass; ?>;

/**
 *
 */
class <?php echo $testClassName; ?> extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }

<?php if(!empty($methods)): ?>
<?php foreach($methods as $method): ?>
    /**
     * @test
     * @covers <?php echo $originalClass ?>::<?php echo $method . PHP_EOL;?>
     * @todo   Implement <?php echo $method;?>().
     */
    public function <?php echo $method;?>()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
<?php endforeach; ?>
<?php else: ?>
    /**
     * @test
     * @todo   Implement <?php echo $originalClass . PHP_EOL;?>
     */
    public function noTestCase()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
<?php endif; ?>
}
