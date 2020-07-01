<?php
declare (strict_types=1);

namespace App\Services;

use App\Facades\ErpApi;
use App\Models\PushTemplates;
use Illuminate\Support\Facades\Log;

class PushServices
{

    /**
     * 每日领取水滴  午餐
     * @param array $to
     * @author sunshine
     */
    public function dailyWaterDropRemindLunch(array $to)
    {
        try {
            $this->send(PushTemplates::pushTemplateBySceneCode('daily_water_drop_remind_lunch'), $to);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * 每日领取水滴  晚餐
     *
     * @param array $to
     * @author sunshine
     */
    public function dailyWaterDropRemindDinner(array $to)
    {
        try {
            $this->send(PushTemplates::pushTemplateBySceneCode('daily_water_drop_remind_dinner'), $to);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * 果树收获72小时内
     *
     * @param array $to
     * @author sunshine
     */
    public function harvestWithinSeventyTwoHours(array $to)
    {
        try {
            $this->send(PushTemplates::pushTemplateBySceneCode('harvest_within_seventy_two_hours'), $to);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * 红包签到提醒
     *
     * @param array $to
     * @throws \App\Exceptions\ErpRequestException
     * @author sunshine
     */
    public function signInTip(array $to)
    {
        try {
            $this->send(PushTemplates::pushTemplateBySceneCode('sign_in_tip'), $to,82,'hb_app_id');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @param PushTemplates $pushTemplates
     * @param array $to
     * @param $news_type
     * @param $driver
     * @throws \App\Exceptions\CryptMessageException
     * @throws \App\Exceptions\ErpRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author sunshine
     */
    private function send(PushTemplates $pushTemplates, array $to, $news_type = 81,$driver='app_id')
    {
        $title = $pushTemplates->title;
        $content = $pushTemplates->content;
        $jump_url = $pushTemplates->jump_url;
        ErpApi::setAppId($driver);
        switch ($pushTemplates->push_type) {
            case PushTemplates::PUSH_TYPE_SITE_VALUE :
                ErpApi::sendSiteMsg($to, $title, $content, $jump_url, $news_type);
                break;
            case PushTemplates::PUSH_TYPE_PUSH_VALUE :
                ErpApi::sendPush($to, $title, $content, $jump_url, $news_type);
                break;
            case PushTemplates::PUSH_TYPE_SITE_PUSH_VALUE :
                try {
                    ErpApi::sendSiteMsg($to, $title, $content, $jump_url, $news_type);
                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
                } finally {
                    ErpApi::sendPush($to, $title, $content, $jump_url, $news_type);
                }
                break;
            default :
                break;
        }
        return;
    }

    /**
     * @param string $code
     * @return mixed
     */
    public function findTemplateByCode($code = '')
    {
        return PushTemplates::where('scene_code', $code)
            ->firstOrFail();
    }



}
