<?php


namespace App\Admin\Actions\Limiter;


use App\Models\Limiter;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class Remove extends RowAction
{
    public $name = '解禁';

    public function handle(Model $model, Request $request)
    {
        $ip = long2ip($request->get('_key'));
        $keys = [];
        $keys[] = sprintf('%s%s', config('limiter.init_key'), $ip);
        $keys[] = sprintf('%s%s', config('limiter.disabled_key'), $ip);
        Redis::del($keys);
        return $this->response()->success('解禁成功')->refresh();
    }

    public function dialog()
    {
        $this->confirm('确定要解禁吗？');
    }

    /**
     * 重写查询数据
     *
     * @param Request $request
     * @return mixed|null
     */
    public function retrieveModel(Request $request)
    {
        return new Limiter;
    }
}
