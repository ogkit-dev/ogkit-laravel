<?php

namespace OgKit\Tests;

use OgKit\Facades\OgKit;
use OgKit\OgKitServiceProvider;
use Orchestra\Testbench\TestCase;

class OgKitTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [OgKitServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return ['OgKit' => OgKit::class];
    }

    public function test_service_provider_is_registered(): void
    {
        $this->assertInstanceOf(\OgKit\OgKit::class, $this->app->make('ogkit'));
    }

    public function test_facade_resolves(): void
    {
        $this->assertInstanceOf(\OgKit\OgKit::class, OgKit::getFacadeRoot());
    }

    public function test_config_is_loaded(): void
    {
        $this->assertIsArray(config('ogkit'));
    }
}
