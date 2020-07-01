<?php

return [
    'host' => 'https://wuliu.market.alicloudapi.com',
    'path' => '/kdi',

    'app_code' => env('LOGISTICS_APP_CODE', ''),

    'cache' => env('LOGISTICS_CACHE', false),

    'ttl' => env('LOGISTICS_TTL', 3600),
];
