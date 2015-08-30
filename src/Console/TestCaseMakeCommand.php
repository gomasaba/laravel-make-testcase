<?php

namespace LaravelMakeTestCase\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TestCaseMakeCommand extends Command
{
    use \Illuminate\Console\AppNamespaceDetectorTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:testcase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new test case';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'TestCase';

    /**
     * Create a new console command instance.
     *
     * @param $appPath
     * @param $basePath
     */
    public function __construct($appPath, $basePath)
    {
        parent::__construct();
        $this->appPath = $appPath;
        $this->testPath = $basePath . DIRECTORY_SEPARATOR . 'tests';
        $this->generator = new \LaravelMakeTestCase\Generator($this->appPath, $this->getAppNamespace(),
            $this->testPath);
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', null, InputOption::VALUE_NONE, 'Generate All TestCase.'],
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::OPTIONAL, 'The name of the class'],
        ];
    }

    /**
     *
     */
    public function fire()
    {
        $files = $this->generator->getFiles();
        if ($this->option('all')) {
            foreach ($files as $file) {
                $this->generate($file);
            }
        } else {
            $name = $this->argument('name');
            if ($name) {
                $file = $this->generator->detectInputName($name);
            } else {
                $file = $this->choice('select target file', $files);
            }
            $this->generate($file);
        }
    }

    /**
     * generate
     *
     * @param $file
     */
    public function generate($file)
    {
        $relative = $this->generator->detectOutPath($file, true);
        if ($this->generator->outFileExists($file)) {
            if (!$this->confirm($relative . ' exists. Override ?')) {
                $this->comment('skipped');
                return;
            }
        }
        $this->generator->generate($file);
        $this->comment($relative . ' created successfully.');
    }
}
