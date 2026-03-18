<?php

namespace OgKit;

class OgKit
{
    public function template(string $html): string
    {
        return '<template data-og-template>'.$html.'</template>';
    }

    public function url(string $pageUrl): string
    {
        $apiKey = config('ogkit.api_key');
        $baseUrl = config('ogkit.base_url');

        $imageUrl = rtrim($baseUrl, '/').'/'.$apiKey.'.jpeg?url='.urlencode($pageUrl);

        $secretKey = config('ogkit.secret_key');

        if ($secretKey) {
            $signature = hash_hmac('sha256', $pageUrl, $secretKey);
            $imageUrl .= '&signature='.$signature;
        }

        return $imageUrl;
    }

    public function meta(string $title, string $description, ?string $url = null, string $type = 'website'): string
    {
        $url = $url ?? request()->url();
        $imageUrl = $this->url($url);

        return implode("\n", [
            '<meta property="og:title" content="'.e($title).'" />',
            '<meta property="og:description" content="'.e($description).'" />',
            '<meta property="og:type" content="'.e($type).'" />',
            '<meta property="og:url" content="'.e($url).'" />',
            '<meta property="og:image" content="'.e($imageUrl).'" />',
            '<meta name="twitter:card" content="summary_large_image" />',
            '<meta name="twitter:title" content="'.e($title).'" />',
            '<meta name="twitter:description" content="'.e($description).'" />',
            '<meta name="twitter:image" content="'.e($imageUrl).'" />',
        ]);
    }

    public function previewScript(): string
    {
        $environments = config('ogkit.preview_environments', ['local']);

        if (! app()->environment($environments)) {
            return '';
        }

        return '<script defer src="https://cdn.jsdelivr.net/npm/ogkit@1"></script>';
    }
}
