<?php


namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

/**
 * Class ClearCacheController
 * @package App\Admin\Controllers
 */
class ClearCacheController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        Cache::forget(config('app.name') . '*');
        admin_success('操作成功', '缓存已清除');
        return back();
    }
}
