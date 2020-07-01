<?php
//ERP接口相关配置

return [
    'app_id'     => env('ERP_APP_ID', '10062'),
    'hb_app_id'     => env('ERP_HB_APP_ID', '10065'),
    'app_key'    => env('ERP_APP_KEY'),
    'secret_key' => env('ERP_SECRET_KEY'),
    'host'       => env('ERP_API_HOST'),
    'openssl'    => [
        'key'    => env('ERP_OPENSSL_KEY'),
        'iv'     => env('ERP_OPENSSL_IV'),
        'method' => env('ERP_OPENSSL_METHOD')
    ],

    'api_urls' => [
        'user_info'  => '/open/v1/user',      //获取用户信息
        'msg_push'   => '/user/v1/messagePush',//push
        'msg_site'   => '/user/v1/messageSite',//站内信
        'withdrawal' => '/withdrawal/v1/externalWithdrawal'
    ]
];
