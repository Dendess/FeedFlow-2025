<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    //MARCHE
    protected $listen = [
        \App\Events\SurveyAnswerSubmitted::class => [
            \App\Listeners\SendNewAnswerNotification::class,
        ],
    ];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
