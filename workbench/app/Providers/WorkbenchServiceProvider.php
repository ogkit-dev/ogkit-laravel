<?php

namespace Workbench\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WorkbenchServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(dirname(__DIR__, 2).'/resources/views', 'workbench');

        Route::get('/', function () {
            $canonicalUrl = url('/');

            return view('workbench::ogkit-demo', [
                'title' => 'OG Kit Laravel Workbench Demo',
                'description' => 'A local Testbench page for verifying OG meta tags and image generation.',
                'canonicalUrl' => $canonicalUrl,
                'imageUrl' => app('ogkit')->url($canonicalUrl),
            ]);
        });
    }
}
