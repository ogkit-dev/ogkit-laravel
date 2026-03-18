<?php

namespace OgKit;

use Illuminate\Support\ServiceProvider;

class OgKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ogkit.php', 'ogkit');

        $this->app->singleton('ogkit', function () {
            return new OgKit;
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/ogkit.php' => config_path('ogkit.php'),
            ], 'ogkit-config');
        }
    }
}
