<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('stage', 'StageController');
    $router->resource('goods', 'GoodsController');
    $router->resource('receive', 'ReceiveGoodsController');
    $router->resource('raiders', 'RaidersController');
    $router->resource('limiter', 'LimiterController');
    $router->resource('pushtemplates', 'PushTemplatesController');
    $router->resource('share', 'ShareController');
    $router->resource('withdraw', 'BagWithdrawLogController');
    $router->resource('task-state', 'TaskStateController');

    $router->get('setting', 'SettingController@setting');

    $router->get('clear-cache', 'ClearCacheController@clear')->name('admin.clear.cache');

//    红包配置
    $router->get('bag-setting', 'BagSettingController@index');
});
