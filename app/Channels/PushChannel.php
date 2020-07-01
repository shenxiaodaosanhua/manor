<?php


namespace App\Channels;

use App\Traits\ErpRequestTrait;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

/**
 * Class PushChannel
 * @package App\Channels
 */
class PushChannel
{
    use ErpRequestTrait;

    /**
     * 发送指定的通知.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $result = $notification->toSend($notifiable);

        $this->sendPush($result);
    }

    /**
     * 发送push推送
     * @param $data
     */
    protected function sendPush($data)
    {
        $customData = [
            'appid'    => config('erp.app_id'),
            'url_type' => $data['jump_url'],
        ];

        $users = array_map(function ($user) {
            return $user['uid'];
        }, $data['to']);
        $users = array_unique($users);

        $params = [
            'title'      => $data['title'],
            'uid'        => implode($users, ','),
            'content'    => $data['content'],
            'newsType'   => 81,
            'link' => json_encode($customData),
        ];
        $url = config('erp.host') . config('erp.api_urls.msg_push');
        $result = $this->get($url, $params);
        Log::info($result);
    }
}
