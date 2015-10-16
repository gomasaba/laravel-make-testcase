<?php

namespace LaravelMakeTestCase;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\PhpEngine;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionMethod;

class Generator
{
    /**
     * @var
     */
    protected $appPath;
    /**
     * @var
     */
    protected $appNamespace;
    /**
     * @var
     */
    protected $testPath;
    /**
     * @var
     */
    protected $filesystem;
    /**
     * @var
     */
    protected $files;

    /**
     * @param $appPath
     * @param $appNamespace
     * @param $testPath
     */
    public function __construct($appPath, $appNamespace, $testPath)
    {
        $this->appPath = $appPath;
        $this->appNamespace = $appNamespace;
        $this->testPath = $testPath;
        $this->filesystem = new Filesystem;
    }

    /**
     * getFiles
     * find under app directory php files.
     *
     * @return mixed
     */
    public function getFiles()
    {
        if (!empty($this->files)) {
            return $this->files;
        }
        $dirIterator = new RecursiveDirectoryIterator($this->appPath);
        $iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $node) {
            if ($node->getExtension() === 'php') {
                $relative = ltrim(str_replace($this->appPath, '', $node->getPathname()), DIRECTORY_SEPARATOR);
                if ($this->existsClass($relative)) {
                    $this->files[] = $relative;
                }
            }
        }
        return $this->files;
    }

    /**
     * detectInputName
     * convert input class name to file path.
     *
     * @param $name
     * @return mixed
     */
    public function detectInputName($name)
    {
        $class = $this->appNamespace . $name;
        if (!class_exists($class)) {
            throw new \InvalidArgumentException($name . ' class not exists');
        }
        $file = str_replace('\\', '/', $name . '.php');
        return $file;
    }

    /**
     * existsClass
     * check class exists.
     *
     * @param $file
     * @return bool
     */
    public function existsClass($file)
    {
        $className = $this->detectClassName($file);
        return class_exists($className);
    }

    /**
     * detectClassName
     * convert file to class name.
     *
     * @param $file
     * @return string
     */
    public function detectClassName($file)
    {
        $file = str_replace('.php', '', $file);
        $file = str_replace('/', '\\', $file);
        return $this->appNamespace . $file;
    }

    /**
     * detectTestClassName
     * convert file to TestCase class.
     *
     * @param $file
     * @return string
     */
    public function detectTestClassName($file)
    {
        $pahinfo = pathinfo($file);
        $className = str_replace('/', '', $pahinfo['dirname']) . $pahinfo['filename'] . 'Test';
        return $className;
    }

    /**
     * detectOutPath
     *
     * @param $file
     * @param bool|false $relative
     * @return string
     */
    public function detectOutPath($file, $relative = false)
    {
        $info = pathinfo($file);
        $outfile = $info['filename'] . 'Test.php';
        $relativePath = $info['dirname'] . DIRECTORY_SEPARATOR . $outfile;
        if ($relative) {
            return $relativePath;
        }
        return $this->testPath . DIRECTORY_SEPARATOR . $relativePath;
    }

    /**
     * getMethods
     *
     * @param $file
     * @return array
     */
    public function getMethods($file)
    {
        $className = $this->detectClassName($file);
        $return = [];
        $class = new ReflectionClass($className);
        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (!$this->isTraitMethod($class, $method->getName()) &&
                $method->getDeclaringClass()->getName() == $className &&
                !$this->isMagicMethod($method->getName())
            ) {

                $return[] = $method->getName();
            }
        }
        return $return;
    }

    /**
     * isMagicMethod
     *
     * @param $methodName
     * @return bool
     */
    public function isMagicMethod($methodName)
    {
        $methods = [
            '__construct',
            '__destruct',
            '__call',
            '__callStatic',
            '__get',
            '__set',
            '__isset',
            '__unset',
            '__sleep',
            '__wakeup',
            '__toString',
            '__invoke',
            '__set_state',
            '__clone',
            '__debugInfo',
        ];
        return in_array($methodName, $methods);
    }

    /**
     * isTraitMethod
     *
     * @param ReflectionClass $class
     * @param $methodName
     * @return bool
     */
    public function isTraitMethod(ReflectionClass $class, $methodName)
    {
        $traits = $class->getTraits();
        if (empty($traits)) {
            return false;
        }
        foreach ($traits as $traitClassName => $reflectionClass) {
            foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->getName() == $methodName) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * render
     *
     * @param $file
     * @return string
     */
    public function render($file)
    {
        $engine = new PhpEngine;
        return $engine->get(__DIR__ . DIRECTORY_SEPARATOR . 'template.php', [
            'originalClass' => $this->detectClassName($file),
            'testClassName' => $this->detectTestClassName($file),
            'methods' => $this->getMethods($file),
        ]);
    }

    /**
     * write
     *
     * @param $content
     * @param $path
     */
    public function write($content, $path)
    {
        if ($this->filesystem->exists($path)) {

        }
        if (!$this->filesystem->isDirectory(dirname($path))) {
            $this->filesystem->makeDirectory(dirname($path), 0777, true, true);
        }
        $this->filesystem->put($path, $content);
    }

    /**
     * outFileExists
     *
     * @param $file
     * @return bool
     */
    public function outFileExists($file)
    {
        $path = $this->detectOutPath($file);
        return $this->filesystem->exists($path);
    }

    /**
     * generate
     *
     * @param $file
     */
    public function generate($file)
    {
        $path = $this->detectOutPath($file);
        $content = $this->render($file);
        $this->write($content, $path);
    }
}