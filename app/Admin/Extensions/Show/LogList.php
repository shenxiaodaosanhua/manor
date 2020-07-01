<?php


namespace App\Admin\Extensions\Show;


use App\Facades\Logistics;
use Encore\Admin\Show\AbstractField;

/**
 * Class LogList
 * @package App\Admin\Extensions\Show
 */
class LogList extends AbstractField
{
    /**
     * @var bool
     */
    public $escape = false;

    /**
     * @var bool
     */
    public $border = true;


    /**
     * @param array $log
     * @return mixed|string
     */
    public function render($log = [])
    {
        $logList = '';
        if (! $this->value['number']) {
            return $logList;
        }
        $logs = Logistics::getWorkLog($this->value['number']);
        foreach ($logs['result']['list'] as $item) {
            $logList .= "<p>{$item['status']}<span>{$item['time']}</span></p>";
        }
        return $logList;
    }
}
