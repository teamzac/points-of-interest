<?php

namespace TeamZac\POI;

use Illuminate\Support\ServiceProvider;

class PointsOfInterestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('points-of-interest.php'),
            ], 'config');

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'points-of-interest');

        // Register the main class to use with the facade
        $this->app->singleton('poi', function () {
            return new Manager($this->app);
        });
    }
}
