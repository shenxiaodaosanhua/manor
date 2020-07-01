<?php


namespace App\Services;


use App\Events\BehaviorEvent;
use App\Models\BagLog;
use App\Models\BagSetting;
use App\Models\BagWithdrawLog;
use App\Models\Behavior;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BagService
 * @package App\Services
 */
class BagService
{

    /**
     * @var BagLogService
     */
    protected $bagLogService;

    /**
     * @var WithdrawalService
     */
    protected $withdrawalService;

    /**
     * BagService constructor.
     * @param BagLogService $bagLogService
     * @param WithdrawalService $withdrawalService
     */
    public function __construct(BagLogService $bagLogService, WithdrawalService $withdrawalService)
    {
        $this->bagLogService = $bagLogService;
        $this->withdrawalService = $withdrawalService;
    }

    /**
     * 获取用户信息
     * @param User $user
     * @return User
     */
    public function findUserInfoAndUpdateSignNumber(User $user)
    {
        $todayBagLog = $this->bagLogService->findBagLogByToday($user);

        $yesterday = now()->subDays(1);
        $yesterdayBagLog = $this->bagLogService->findBagSignByDay($user, $yesterday);
        if ((! $yesterdayBagLog) && ($todayBagLog['is_sign'] == 0)) {
            $user->sign_number = 0;
            $user->save();
        }

        return $user->refresh();
    }

    /**
     * 签到
     * @param User $user
     * @return mixed
     * @throws \Throwable
     */
    public function signIn(User $user)
    {
        return DB::transaction(function () use ($user) {
            $bagLog = $this->bagLogService->findBagLogByToday($user);
            $user->refresh();

            if ($bagLog['is_sign'] == 1) {
                throw new HttpException(403, '您今天已签到，请明天再来');
            }

            $days = $this->bagLogService->findBagSignDays($user);
            $dayCount = count($days);


            $data = [
                'user_id' => $user->id,
                'bhv_type' => 'bag-sign-days',
                'trace_id' => Behavior::TRACE_BAG,
            ];
            event(new BehaviorEvent($data));

            if ($dayCount >= 7) {
                $dayCount = 1;
            }

            $amount = $this->getBagAmount($dayCount);

            $isLast = BagLog::LAST_NOT;
//            已连续签到6天，当前最后一天签到
            if ($dayCount == 6) {
                $isLast = BagLog::LAST_YES;
                $data = [
                    'user_id' => $user->id,
                    'bhv_type' => 'sig-sign-day-7',
                    'trace_id' => Behavior::TRACE_BAG,
                ];
                event(new BehaviorEvent($data));
            }

            $log = $this->bagLogService->create($user, $amount, BagLog::TYPE_SIGN, $isLast);

            $user->increment('amount', $amount);
            $user->refresh();

            return $log;
        });
    }


    /**
     * 获取红包金额
     * @param int $dayNumber
     * @return float|int
     */
    public function getBagAmount(int $dayNumber)
    {
        $config = $this->findBagSetting() ?? config('bag.setting');
        $min = 0;
        $max = 0;

        if ($dayNumber >= 7) {
            $dayNumber = 1;
        }

        if ($dayNumber < 6) {
            $min = $config['ago_six']['min'] * 100;
            $max = $config['ago_six']['max'] * 100;
        }

        if ($dayNumber == 6) {
            $min = $config['to_seven']['min'] * 100;
            $max = $config['to_seven']['max'] * 100;
        }

        return mt_rand($min, $max) / 100;
    }

    /**
     * 获取配置参数
     * @return mixed
     */
    public function findBagSetting()
    {
        $bagSetting = BagSetting::orderBy('id', 'desc')->first();
        return $bagSetting->content;
    }

    /**
     * 提现红包
     * @param User $user
     * @return mixed
     * @throws \Throwable
     */
    public function withdraw(User $user)
    {
        return DB::transaction(function () use ($user) {
            $number = config('withdraw-number');
            $user->refresh();
            if ($user->amount < $number) {
                throw new HttpException(403, '当前金额未达提现额度');
            }

            //插入流水记录
            $log = BagWithdrawLog::create([
                'user_id' => $user->id,
                'amount' => $user->amount,
            ]);

//            请求erp提现
            $this->withdrawalService->withdrawal($user->openid, $user->amount, $log->no);

//            减少金额
            $user->decrement('amount', $user->amount);
            $user->refresh();

            return $user;
        });
    }
}
