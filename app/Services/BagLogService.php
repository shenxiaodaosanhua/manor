<?php


namespace App\Services;


use App\Models\BagLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Class BagLogService
 * @package App\Services
 */
class BagLogService
{
    /**
     * @param User $user
     * @param float $amount
     * @param int $type
     * @param int $isLast
     * @return mixed
     */
    public function create(User $user, float $amount, $type = BagLog::TYPE_SIGN, $isLast = BagLog::LAST_NOT)
    {
        $data = [
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => $type,
            'is_last' => $isLast,
        ];

        return BagLog::create($data);
    }

    /**
     * 获取签到数据列表
     * @param User $user
     * @return array
     */
    public function findBagLogs(User $user)
    {
        $days = $this->findBagSignDays($user);
        if (count($days) >= 7) {
            $days = [];
        }

        $todayLog = $this->findBagLogByToday($user);
        array_unshift($days, $todayLog);

        $number = 1;
        for ($i = count($days); $i < 7; $i++) {
            $data = [
                'amount' => 0,
                'sign_date' => today()->addDays($number)->toDateString(),
                'is_sign' => 0,
                'is_today' => 0,
            ];
            array_unshift($days, $data);
            $number++;
        }

        $days = $this->arraySort($days, 'sign_date', SORT_ASC);

        return $days;
    }

    /**
     * 数组排序
     * @param $array
     * @param $keys
     * @param int $sort
     * @return mixed
     */
    protected function arraySort($array, $keys, $sort = SORT_DESC) {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }

    /**
     * 获取当天签到信息
     * @param User $user
     * @return array
     */
    public function findBagLogByToday(User $user)
    {
        $bagLog = BagLog::where('user_id', $user->id)
                    ->whereDate('created_at', today())
                    ->first();

        if (! $bagLog) {
            return [
                'sign_date' => today()->toDateString(),
                'amount' => 0,
                'is_sign' => 0,
                'is_today' => 1,
            ];
        }

        return [
            'sign_date' => $bagLog->created_at->format('Y-m-d'),
            'amount' => $bagLog->amount,
            'is_sign' => 1,
            'is_today' => 1,
        ];
    }

    /**
     * 获取连续签到信息
     * @param User $user
     * @return array
     */
    public function findBagSignDays(User $user)
    {
        $data = [];

        for ($i = 1; $i <= 7; $i++) {
            $day = today()->subDays($i);
            $bagLog = $this->findBagSignByUserIdAndDay($user, $day);
            if (! $bagLog) {
                break;
            }

            if ($bagLog->is_last) {
                break;
            }

            $data[] = [
                'sign_date' => $bagLog->created_at->format('Y-m-d'),
                'amount' => $bagLog->amount,
                'is_sign' => 1,
                'is_today' => 0,
            ];
        }

        return $data;
    }

    /**
     * 获取指定日期签到数据
     * @param User $user
     * @param Carbon $carbon
     * @return mixed
     */
    public function findBagSignByUserIdAndDay(User $user, Carbon $carbon)
    {
        return BagLog::where('user_id', $user->id)
                    ->whereDate('created_at', $carbon)
                    ->first();
    }

    /**
     * 获取指定日期数据
     * @param User $user
     * @param Carbon $carbon
     * @return mixed
     */
    public function findBagSignByDay(User $user, Carbon $carbon)
    {
        return BagLog::where('user_id', $user->id)
            ->whereDate('created_at', $carbon)
            ->first();
    }

    /**
     * @param BagLog $bagLog
     * @return mixed
     */
    public function addUserSignNumber(BagLog $bagLog)
    {
        $user = User::find($bagLog->user_id);

        $user->increment('sign_number', 1);
        $user->refresh();

        return $user;
    }

    /**
     * @param Collection $users
     */
    public function updateSignInDeBug(Collection $users)
    {
        $users->each(function ($user) {
            if (! $user->bag) {
                return $user;
            }

            $this->checkSigInDays($user->bag->sortBy('created_at'));

            return $user;
        });
    }

    /**
     * @param Collection $logs
     */
    protected function checkSigInDays(Collection $logs)
    {
        $number = 1;
        $day = Carbon::parse();

        $logs->each(function ($log) use (&$number, &$day) {
            if ($number == 1) {
                $day = $log->created_at;
            }

            $result = $this->checkBugLogDay($log, $day);
            if (! $result) {
                $number = 1;
                return $log;
            }

            $isLast = $number % 7;

            $day->addDay();
            $number++;

            $log->is_last = ($isLast == 0 ? 1 : 0);
            $log->save();

            return  $log;
        });
    }

    /**
     * @param BagLog $bagLog
     * @param Carbon $carbon
     * @return bool
     */
    protected function checkBugLogDay(BagLog $bagLog, Carbon $carbon)
    {
        if ($carbon->isSameDay($bagLog->created_at)) {
            return true;
        }

        return false;
    }
}
