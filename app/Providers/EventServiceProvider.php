<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        Registered::class => [
//            SendEmailVerificationNotification::class,
//        ],

        'App\Events\Planting' => [
            'App\Listeners\PlantingListener',
        ],

        'App\Events\Manor\DailyWaterDropRemindLunch' => [
            'App\Listeners\Manor\DailyWaterDropRemindLunchListener',
        ],
        'App\Events\Manor\DailyWaterDropRemindDinner' => [
            'App\Listeners\Manor\DailyWaterDropRemindDinnerListener',
        ],
        'App\Events\Manor\HarvestWithinSeventyTwoHours' => [
            'App\Listeners\Manor\HarvestWithinSeventyTwoHoursListener',
        ],
        'App\Events\RedEnvelopeSignIn\SignInTip' => [
            'App\Listeners\RedEnvelopeSignIn\SignInTipListener',
        ],
        'App\Events\BehaviorEvent' => [
            'App\Listeners\BehaviorListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
