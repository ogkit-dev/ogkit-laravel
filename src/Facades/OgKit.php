<?php

namespace OgKit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string url(string $pageUrl)
 * @method static string meta(string $title, string $description, ?string $url = null, string $type = 'website')
 * @method static string previewScript()
 */
class OgKit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ogkit';
    }
}
