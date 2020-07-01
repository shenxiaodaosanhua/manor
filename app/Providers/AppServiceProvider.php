<?php

namespace App\Providers;

use App\Models\BagLog;
use App\Models\Plant;
use App\Models\PlantLog;
use App\Models\Raiders;
use App\Models\TaskState;
use App\Models\User;
use App\Observers\BagLogObserver;
use App\Observers\PlantLogObserver;
use App\Observers\PlantObserver;
use App\Observers\RaidersObserver;
use App\Observers\TaskStateObserver;
use App\Observers\UserObserver;
use Encore\Admin\Config\Config;
use Encore\Admin\Config\ConfigModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $table = config('admin.extensions.config.table', 'admin_config');
        if (Schema::hasTable($table)) {
            Config::load();
        }

//        if (Schema::hasTable($table)) {
//            if (class_exists(ConfigModel::class)) {
//                if ($json = Cache::get('configs')) {
//                    $configs = json_decode($json, true);
//                } else {
//                    $configs = ConfigModel::all(['name', 'value']);
//                    Cache::put('configs', json_encode($configs));
//                }
//
//                foreach ($configs as $config) {
//                    config([$config['name'] => $config['value']]);
//                }
//            }
//        }

        Plant::observe(PlantObserver::class);
        PlantLog::observe(PlantLogObserver::class);
        Raiders::observe(RaidersObserver::class);
        TaskState::observe(TaskStateObserver::class);
        User::observe(UserObserver::class);
        BagLog::observe(BagLogObserver::class);

//        兼容低版本数据库索引问题
        Schema::defaultStringLength(191);
    }
}
