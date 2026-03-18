# OG Kit for Laravel

A Laravel package for [OG Kit](https://ogkit.dev) — generate Open Graph images from your existing HTML and CSS.

## Installation

```bash
composer require ogkit/ogkit-laravel
```

Add your API key to `.env`:

```env
OGKIT_API_KEY=your-api-key
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag=ogkit-config
```

## Usage

### Meta tags

Use the `@ogMeta` Blade directive in your `<head>` to render all the Open Graph and Twitter Card meta tags:

```blade
<head>
    @ogMeta($post->title, $post->excerpt)
</head>
```

This renders:

```html
<meta property="og:title" content="My Post Title" />
<meta property="og:description" content="A short excerpt." />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://yoursite.com/posts/my-post" />
<meta property="og:image" content="https://ogkit.dev/img/your-api-key.jpeg?url=https%3A%2F%2Fyoursite.com%2Fposts%2Fmy-post" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="My Post Title" />
<meta name="twitter:description" content="A short excerpt." />
<meta name="twitter:image" content="https://ogkit.dev/img/your-api-key.jpeg?url=https%3A%2F%2Fyoursite.com%2Fposts%2Fmy-post" />
```

The URL defaults to the current request URL. You can pass it explicitly, along with a custom type:

```blade
@ogMeta($title, $description, $canonicalUrl, 'article')
```

### Local preview

Use the `@ogPreview` directive to load the OG Kit preview script. It only outputs in local development, so you can keep it in your layout permanently:

```blade
<head>
    @ogPreview
    @ogMeta($title, $description)
</head>
```

Then append `?ogkit-render` to any URL to see your OG image template rendered at 1200x630px.

### URL generation

If you need the image URL directly (e.g. in an API response or a custom meta tag setup), use the facade:

```php
use OgKit\Facades\OgKit;

$imageUrl = OgKit::url('https://yoursite.com/posts/my-post');
// https://ogkit.dev/img/your-api-key.jpeg?url=https%3A%2F%2Fyoursite.com%2Fposts%2Fmy-post
```

## Wildcard domains (signed URLs)

If your API key uses a wildcard domain pattern, add your secret key to `.env`:

```env
OGKIT_SECRET_KEY=your-secret-key
```

When a secret key is configured, all generated URLs automatically include an HMAC-SHA256 signature:

```
https://ogkit.dev/img/your-api-key.jpeg?url=...&signature=abc123...
```

## Configuration

```php
// config/ogkit.php

return [
    // Your OG Kit API key
    'api_key' => env('OGKIT_API_KEY', ''),

    // Secret key for wildcard domain signing
    'secret_key' => env('OGKIT_SECRET_KEY', ''),

    // OG Kit API base URL
    'base_url' => env('OGKIT_BASE_URL', 'https://ogkit.dev/img/'),

    // Environments where the preview script is loaded
    'preview_environments' => ['local'],
];
```

## License

MIT
