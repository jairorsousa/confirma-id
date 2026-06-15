<?php

return [
    'uploads' => [
        'max_file_kb' => (int) env('CONFIRMAID_UPLOAD_MAX_FILE_KB', 5120),
        'allowed_image_mimetypes' => [
            'image/jpeg',
            'image/png',
            'image/webp',
        ],
        'extension_by_mimetype' => [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ],
    ],

    'rate_limits' => [
        'partner_api_per_minute' => (int) env('CONFIRMAID_PARTNER_API_RATE_PER_MINUTE', 60),
        'partner_web_per_minute' => (int) env('CONFIRMAID_PARTNER_WEB_RATE_PER_MINUTE', 30),
    ],
];
