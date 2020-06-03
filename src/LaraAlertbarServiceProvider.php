<?php

namespace DigiPearl\LaraAlertbar;

use Illuminate\Support\ServiceProvider;

class LaraAlertbarServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the package services.
     *
     * @return void
     * @author Digi Pearl
     */
    public function boot()
    {
        /*
         * Registering the helper methods to package
         */
        $this->registerHelpers();

        /*
        * Registering the Package Views
        */
        $this->registerViews();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
    }

    /**
     * Register helpers file
     *
     * @return void
     * @author Digi Pearl
     */
    public function registerHelpers()
    {
        // Load the helpers in src/functions.php
        if (file_exists($file = __DIR__ . '/functions.php')) {
            require $file;
        }
    }

    /**
     * Register the package's views.
     *
     * @return void
     * @author Digi Pearl
     */
    private function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laralertbar');
    }

    /**
    * Register the package's publishable resources.
    *
    * @return void
    * @author Digi Pearl
    */
    private function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/laralertbar')
        ], 'laralertbar-view');

        $this->publishes([
            __DIR__ . '/config/laralertbar.php' => config_path('laralertbar.php')
        ], 'laralertbar-config');

        $this->publishes([
            __DIR__ . '/../resources/js' => public_path('vendor/laralertbar')
        ], 'laralertbar-asset');
    }

    /**
     * Register the application services.
     *
     * @return void
     * @author Digi Pearl
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/config/laralertbar.php', 'laralertbar');

        // Binding required classes to app
        $this->app->bind(
            'DigiPearl\LaraAlertbar\Storage\SessionStore',
            'DigiPearl\LaraAlertbar\Storage\AlertSessionStore',
            'DigiPearl\LaraAlertbar\ToLaraAlertbar'
        );

        // Register the main class to use with the facade
        $this->app->singleton('alert', function ($app) {
            return $this->app->make(Toaster::class);
        });

        if ($this->app->runningInConsole()) {
            // Registering package commands.
            $this->commands([
                Console\PublishCommand::class,
            ]);
        }
    }
}
