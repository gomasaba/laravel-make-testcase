# laravel-make-testcase

add make:testcase command for laravel

[![Build Status](https://travis-ci.org/ootatter/laravel-make-testcase.svg?branch=master)](https://travis-ci.org/ootatter/laravel-make-testcase)
[![Latest Stable Version](https://poser.pugx.org/ootatter/laravel-make-testcase/version)](https://packagist.org/packages/ootatter/laravel-make-testcase)
[![Total Downloads](https://poser.pugx.org/ootatter/laravel-make-testcase/downloads)](https://packagist.org/packages/ootatter/laravel-make-testcase)
[![Latest Unstable Version](https://poser.pugx.org/ootatter/laravel-make-testcase/v/unstable)](//packagist.org/packages/ootatter/laravel-make-testcase)
[![License](https://poser.pugx.org/ootatter/laravel-make-testcase/license)](https://packagist.org/packages/ootatter/laravel-make-testcase)

### Install


```
composer require ootatter/laravel-make-testcase
```

Add the following in config/app.php:

```
'providers' => [
    LaravelMakeTestCase\GeneratorServiceProvider::class,
];
```

### Usage


suggest under app directory php files

```
php artisan make:testcase
```

or input class name.

```
php artisan make:testcase Http\\Controllers\\PostController
```

or all file generate.

```
php artisan make:testcase --all
```

### Generate TestCase File

if generate Http/Controllers/PostController.php output like this.

```
├ tests
  ├ Http
     ├ Controllers
        ├ PostControllerTest.php
```

and PostControllerTest.php like this.


```
<?php
use App\Http\Controllers\PostController;

/**
 *
 */
class HttpControllersPostControllerTest extends TestCase
{
    /**
     * @test
     * @covers App\Http\Controllers\SomeController::publicSomeMethod
     * @todo   Implement publicSomeMethod().
     */
    public function publicSomeMethod()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
```




### License

The MIT License (MIT)

Copyright (c) 2015 ootatter

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
