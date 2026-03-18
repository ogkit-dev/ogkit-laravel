<?php

return [
    'api_key' => env('OGKIT_API_KEY', ''),
    'secret_key' => env('OGKIT_SECRET_KEY', ''),
    'base_url' => env('OGKIT_BASE_URL', 'https://ogkit.dev/img/'),
    'preview_environments' => ['local'],
];
