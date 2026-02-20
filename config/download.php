<?php
// config/download.php

return [
    // ...
    'api' => [
        'timeout'   => (float) env('DOWNLOAD_API_TIMEOUT', 3.0),
        'cache_ttl' => (int) env('DOWNLOAD_API_CACHE_TTL', 300),
        // если нужно ходить в API без пользовательского токена:
        'token'     => env('DOWNLOAD_API_TOKEN', '2|Il5T8eOOS3XlW9lcPN2To7K7OwJhWhcqBR3gXa6Iac94f29e'),
    ],
];
