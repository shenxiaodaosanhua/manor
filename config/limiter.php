<?php

return [
    'max' => env('LIMITER_MAX'),        //  最大次数
    'init_sec' => env('LIMITER_INIT_SEC'),  //  累积时间
    'disabled_sec' => env('LIMITER_DISABLED_SEC'),  //  冻结时长
    'init_key' => env('LIMITER_INIT_KEY'),  //  累计 redis KEY
    'disabled_key' => env('LIMITER_DISABLED_KEY') //    冻结 redis key
];
