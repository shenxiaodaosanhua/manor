<?php

return [
    'today_waters' => [
        'value' => [
            10 => 0.2,
            15 => 0.4,
            20 => 0.4,
        ],
        'state' => 'on',
        'title' => '每日免费领水滴',
        'desc' => '奖励10-20g',
        'image' => 'http://manor.jotrend.net/images/9d346a1d6bc7e84f025194e4c89fc2d8.png',
        'uri' => '',
        'today_number' => '0',
//        'name' => 'today_waters',
    ],
    'today_three_meals' => [
        'value' => [
            30 => 0.2,
            40 => 0.6,
            50 => 0.2,
        ],
        'state' => 'on',
        'title' => '每日三餐福袋',
        'desc' => '每日7-9点，12-14点，18-21点开启福袋可得30-50g',
        'image' => 'http://manor.jotrend.net/images/ee88f55743465792f4bd0d757ca57871.png',
        'uri' => '',
        'today_number' => '0',
//        'name' => 'today_three_meals',
    ],
    'see_shop_waters' => [
        'value' => 10,
        'today_number' => 1,
        'state' => 'on',
        'title' => '浏览美生商城',
        'desc' => '奖励20g',
        'image' => 'http://manor.jotrend.net/images/a490d13aa07f5bb766329b20747e5ccc.png',
        'uri' => '#/shop',
        'app_id' => '10017',
//        'name' => 'see_shop_waters',
    ],
    'shop_order' => [
        'value' => 10,
        'today_number' => 1,
        'state' => 'off',
        'title' => '商城下单可领取水滴数',
        'desc' => '奖励20G',
        'image' => 'http://manor.jotrend.net/images/a490d13aa07f5bb766329b20747e5ccc.png',
        'uri' => '#/shop',
        'app_id' => '10017',
//        'name' => 'shop_order',
    ],
    'food_order' => [
        'value' => 10,
        'today_number' => 1,
        'state' => 'on',
        'title' => '在美食中下单商品',
        'desc' => '奖励50g',
        'image' => 'http://manor.jotrend.net/images/0ed811ccfab28fb19865ba8b11e206d1.png',
        'uri' => '',
        'app_id' => '10021',
//        'name' => 'food_order',
    ],
    'today_bag_waters' => [
        'value' => 20,
        'state' => 'on',
        'title' => '开启每日免费领水滴提醒',
        'desc' => '开启后可领取15g水滴',
        'image' => 'http://manor.jotrend.net/images/b7ef11503ca8c824ece318213eac7654.png',
        'uri' => '',
        'today_number' => '0',
//        'name' => 'today_bag_waters',
    ],
    'today_continuous' => [
        'value' => [
            1 => 5,
            2 => 10,
            3 => 15,
            4 => 20,
            5 => 25,
        ],
        'state' => 'on',
        'title' => '连续签到可领取水滴数',
        'desc' => '',
        'image' => 'http://manor.jotrend.net/images/ee88f55743465792f4bd0d757ca57871.png',
        'uri' => '',
        'today_number' => '0',
//        'name' => 'today_continuous'
    ],
];
