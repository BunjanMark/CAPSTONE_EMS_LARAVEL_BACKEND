<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use App\Events\SendExpoTokenEvent;
use App\Listeners\SaveExpoTokenListener;
use App\Events\ServiceCreatedEvent;
use App\Listeners\NotifyAdminListener;
use App\Listeners\EventCreatedListener;
use App\Events\EventCreatedEvent;
use App\Listeners\PackageCreatedListener;
use App\Events\PackageCreatedEvent;
use App\Services\Communications\TwilioService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TwilioService::class, function ($app) {
            return new TwilioService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       Event::listen(
         SendExpoTokenEvent::class,
         SaveExpoTokenListener::class,
         // service creation notification
         ServiceCreatedEvent::class,
         NotifyAdminListener::class,
        //  event creation notification
        EventCreatedEvent::class,
        EventCreatedListener::class,
        // package creation notification
        PackageCreatedEvent::class,
        PackageCreatedListener::class
       );

       
    }
     
}
