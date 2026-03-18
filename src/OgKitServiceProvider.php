<?php

namespace OgKit;

use Illuminate\Support\Facades\Blade;
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

        Blade::directive('ogMeta', function ($expression) {
            return "<?php echo app('ogkit')->meta($expression); ?>";
        });

        Blade::directive('ogTemplate', function ($expression) {
            if (trim((string) $expression) === '') {
                return '<template data-og-template>';
            }

            return "<?php echo app('ogkit')->template($expression); ?>";
        });

        Blade::directive('endOgTemplate', function () {
            return '</template>';
        });

        Blade::directive('ogPreview', function () {
            return "<?php echo app('ogkit')->previewScript(); ?>";
        });
    }
}
