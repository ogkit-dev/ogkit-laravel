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

    public function test_url_generation(): void
    {
        config(['ogkit.api_key' => 'test123']);

        $url = OgKit::url('https://example.com/page');

        $this->assertEquals(
            'https://ogkit.dev/img/test123.jpeg?url=https%3A%2F%2Fexample.com%2Fpage',
            $url
        );
    }

    public function test_url_generation_with_signed_url(): void
    {
        config([
            'ogkit.api_key' => 'test123',
            'ogkit.secret_key' => 'mysecret',
        ]);

        $url = OgKit::url('https://example.com/page');
        $expectedSignature = hash_hmac('sha256', 'https://example.com/page', 'mysecret');

        $this->assertStringContainsString('&signature='.$expectedSignature, $url);
    }

    public function test_url_generation_without_secret_key_has_no_signature(): void
    {
        config([
            'ogkit.api_key' => 'test123',
            'ogkit.secret_key' => '',
        ]);

        $url = OgKit::url('https://example.com/page');

        $this->assertStringNotContainsString('signature', $url);
    }

    public function test_meta_renders_all_tags(): void
    {
        config(['ogkit.api_key' => 'test123']);

        $html = OgKit::meta('My Title', 'My description', 'https://example.com/page');

        $this->assertStringContainsString('<meta property="og:title" content="My Title" />', $html);
        $this->assertStringContainsString('<meta property="og:description" content="My description" />', $html);
        $this->assertStringContainsString('<meta property="og:type" content="website" />', $html);
        $this->assertStringContainsString('<meta property="og:url" content="https://example.com/page" />', $html);
        $this->assertStringContainsString('<meta property="og:image" content="https://ogkit.dev/img/test123.jpeg?url=https%3A%2F%2Fexample.com%2Fpage" />', $html);
        $this->assertStringContainsString('<meta name="twitter:card" content="summary_large_image" />', $html);
        $this->assertStringContainsString('<meta name="twitter:title" content="My Title" />', $html);
        $this->assertStringContainsString('<meta name="twitter:description" content="My description" />', $html);
        $this->assertStringContainsString('<meta name="twitter:image" content="https://ogkit.dev/img/test123.jpeg?url=https%3A%2F%2Fexample.com%2Fpage" />', $html);
    }

    public function test_meta_escapes_html(): void
    {
        config(['ogkit.api_key' => 'test123']);

        $html = OgKit::meta('Title with "quotes" & <tags>', 'Desc', 'https://example.com');

        $this->assertStringContainsString('content="Title with &quot;quotes&quot; &amp; &lt;tags&gt;"', $html);
    }

    public function test_meta_custom_type(): void
    {
        config(['ogkit.api_key' => 'test123']);

        $html = OgKit::meta('Title', 'Desc', 'https://example.com', 'article');

        $this->assertStringContainsString('<meta property="og:type" content="article" />', $html);
    }

    public function test_preview_script_renders_in_local_environment(): void
    {
        $this->app->detectEnvironment(fn () => 'local');

        $html = OgKit::previewScript();

        $this->assertEquals('<script defer src="https://cdn.jsdelivr.net/npm/ogkit@1"></script>', $html);
    }

    public function test_preview_script_hidden_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $html = OgKit::previewScript();

        $this->assertEquals('', $html);
    }

    public function test_preview_script_respects_custom_environments(): void
    {
        config(['ogkit.preview_environments' => ['local', 'staging']]);
        $this->app->detectEnvironment(fn () => 'staging');

        $html = OgKit::previewScript();

        $this->assertNotEmpty($html);
    }

    public function test_blade_og_meta_directive_compiles(): void
    {
        $compiled = $this->app['blade.compiler']->compileString("@ogMeta('Title', 'Desc', 'https://example.com')");

        $this->assertStringContainsString("app('ogkit')->meta('Title', 'Desc', 'https://example.com')", $compiled);
    }

    public function test_blade_og_preview_directive_compiles(): void
    {
        $compiled = $this->app['blade.compiler']->compileString('@ogPreview');

        $this->assertStringContainsString("app('ogkit')->previewScript()", $compiled);
    }
}
