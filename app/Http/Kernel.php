<?php

namespace App\Http;

use App\Jobs\UpdateDailyQuotes;
use App\Services\QuoteService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Other middleware...
    ];

    protected $middlewareGroups = [
        'web' => [
            // Other web middleware...
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        // Other route middleware...
        'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new UpdateDailyQuotes(app(QuoteService::class)))->daily();
    }
}
