<?php

namespace LaravelMakeTestCase;

use Illuminate\Support\ServiceProvider;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.testcase.make', function ($app) {
            return new Console\TestCaseMakeCommand($app['path'], $app->basePath());
        });

        $this->commands('command.testcase.make');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.testcase.make'
        ];
    }
}
