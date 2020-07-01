<?php

use Illuminate\Http\Request;


Route::namespace('V1')->group(function () {
//    获取用户信息
    Route::post('user', 'UsersController@userInfo');
    Route::post('auth/login', 'UsersController@login');
    //    获取用户信息
    Route::post('hb-user', 'UsersController@hbUserInfo');

//    商城订单下单通知
    Route::post('food-order', 'NotifyController@order');

//    庄园路由
    Route::middleware(['auth.uid'])->prefix('plant')->group(function () {
        Route::get('home', 'HomeController@index');
        Route::get('status', 'HomeController@status');

        Route::post('planting', 'PlantController@planting');
        Route::get('task', 'PlantTaskController@getTasks');
        Route::get('goods', 'GoodsController@index');

        Route::post('receive', 'GoodsController@receive');
        Route::put('receive', 'GoodsController@receiveAddress');
        Route::get('raiders', 'RaidersController@index');
        Route::get('task', 'TaskController@index');
        Route::post('task', 'TaskController@receive');

//        前端数据埋点数据保存
        Route::post('behavior', 'BehaviorController@store');

        //获取签到列表
        Route::get('sign-in', 'TaskController@signInList');
        //获取植物超时时间
        Route::get('plant-time-out', 'PlantController@timeOut');
        Route::get('my-gift', 'UsersController@myGift');
        Route::get('my-gift-logistics', 'UsersController@myGiftLogistics');
        //分享
        Route::get('share', 'PlantShareController@index');
        //获取任务是否可领取状态
        Route::get('task-status', 'TaskController@getTaskIsReceive');

        Route::get('plant-log', 'PlantLogController@index');

        Route::middleware('water')->group(function () {
            Route::post('watering', 'WaterController@Watering');
        });
    });
//  红包签到
    Route::middleware(['auth.uid'])->prefix('bag')->group(function () {
        Route::get('index', 'BagController@user');

//        提现
        Route::put('withdraw', 'BagController@withdraw');

        Route::get('help', 'BagHelpController@index');

        Route::get('sign-in', 'BagController@index');
        Route::post('sign-in', 'BagController@signIn');

//        推送通知开启
        Route::post('notify', 'BagController@openNotify');
        Route::get('notify', 'BagController@getNotify');

        Route::post('behavior', 'BehaviorController@store');
    });
});

