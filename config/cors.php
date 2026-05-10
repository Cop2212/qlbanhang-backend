<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // ❌ KHÔNG dùng *
    'allowed_origins' => [
        'http://localhost:4200',
        'https://qlbanhang-frontend.onrender.com',
        'https://gomab.vn',
        'https://www.gomab.vn',
        'https://api.gomab.vn'
    ],

    'allowed_origins_patterns' => ['https?:\/\/.*\.gomab\.vn$', 'https?:\/\/gomab\.vn$'],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // 🔥 BẮT BUỘC phải true khi dùng cookie
    'supports_credentials' => true,

];
