<?php

namespace App\Providers;

use App\Mail\JobFailedMailable;
use App\Notifications\QueueFailed;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Queue::failing(function (JobFailed $event) {
        //     Mail::to('lekhang2512@gmail.com')->send(new JobFailedMailable($event));
        // });

        $slackUrl = env('SLACK_WEBHOOK_URL');
        Queue::failing(function (JobFailed $event) use ($slackUrl) {
            Notification::route('slack', $slackUrl)->notify(new QueueFailed($event));
        });
    }
}
