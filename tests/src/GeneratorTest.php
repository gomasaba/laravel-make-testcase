<?php

use Illuminate\Filesystem\Filesystem;

class GeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \LaravelMakeTestCase\Generator
     */
    protected $generator;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->generator = new \LaravelMakeTestCase\Generator(app_path(), app_namespace(), tests_path());
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $filesystem = new Filesystem;
        $filesystem->deleteDirectory(tests_path() . DIRECTORY_SEPARATOR . 'Http');
        unset($this->generator);
        parent::tearDown();
    }


    public function testGetFiles()
    {
        $actual = $this->generator->getFiles();
        $this->assertContains('Http/Controllers/Controller.php', $actual);
        $this->assertContains('Http/Controllers/SomeController.php', $actual);
        $this->assertContains('Models/Something.php', $actual);
        $this->assertNotContains('Http/routes.php', $actual);
    }

    public function testDetectInputName()
    {
        $actual = $this->generator->detectInputName('Http\\Controllers\\SomeController');
        $expected = 'Http/Controllers/SomeController.php';
        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Http\Controllers\NothingController class not exists
     */
    public function testDetectInputNameException()
    {
        $this->generator->detectInputName('Http\\Controllers\\NothingController');
    }

    public function testDetectClassName()
    {
        $this->generator->getFiles();
        $actual = $this->generator->detectClassName('Http/Controllers/Controller.php');
        $this->assertSame('DummyApp\Http\Controllers\Controller', $actual);
    }

    public function testDetectTestClassName()
    {
        $this->generator->getFiles();
        $actual = $this->generator->detectTestClassName('Http/Controllers/Controller.php');
        $this->assertSame('HttpControllersControllerTest', $actual);
    }

    public function testDetectOutPath()
    {
        $this->generator->getFiles();
        $actual = $this->generator->detectOutPath('Http/Controllers/Controller.php');
        $expected = tests_path() . DIRECTORY_SEPARATOR . 'Http/Controllers/ControllerTest.php';
        $this->assertSame($expected, $actual);
    }

    public function testDetectOutPathRelative()
    {
        $this->generator->getFiles();
        $actual = $this->generator->detectOutPath('Http/Controllers/Controller.php', true);
        $expected = 'Http/Controllers/ControllerTest.php';
        $this->assertSame($expected, $actual);
    }

    public function testGetMethods()
    {
        $this->generator->getFiles();
        $actual = $this->generator->getMethods('Http/Controllers/SomeController.php');
        $expected = [
            'publicSomeMethod',
            'publicStaticMethod',
        ];
        $this->assertSame($expected, $actual);
    }

    public function testGetMethodsWithTrait()
    {
        $this->generator->getFiles();
        $actual = $this->generator->getMethods('Models/Something.php');
        $expected = [
            'another'
        ];
        $this->assertSame($expected, $actual);
    }

    public function testRender()
    {
        $this->generator->getFiles();
        $actual = $this->generator->render('Http/Controllers/SomeController.php');
        $expected = file_get_contents(fixture_path() . '/SomeControllerTest.php');
        $this->assertSame($expected, $actual);
    }

    public function testWrite()
    {
        $this->generator->getFiles();
        $content = $this->generator->render('Http/Controllers/SomeController.php');
        $path = $this->generator->detectOutPath('Http/Controllers/SomeController.php');
        $this->generator->write($content, $path);
        $expected = tests_path() . DIRECTORY_SEPARATOR . 'Http/Controllers/SomeControllerTest.php';
        $this->assertFileExists($expected);
        $this->generator->outFileExists('Http/Controllers/SomeController.php');
        @unlink($expected);
    }

}