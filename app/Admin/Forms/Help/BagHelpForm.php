<?php


namespace App\Admin\Forms\Help;


use App\Models\BagHelp;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class BagHelpForm extends Form
{
    public $title = '规则配置';

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        try {
            BagHelp::create([
                'content' => $request->get('content'),
            ]);
            admin_success('更新成功');
        } catch (\Exception $exception) {
            admin_error($exception->getMessage());
        }


        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->textarea('content', '活动规则')->default($this->data());
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        $help = BagHelp::orderBy('id', 'desc')->first();
        return  $help->content ?? config('bag.help');
    }
}
