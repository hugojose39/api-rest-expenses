<?php

namespace App\Providers;

use App\Events\Expense\Created;
use App\Listeners\Expense\Created\SendCreatedExpenseNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Created::class => [
            SendCreatedExpenseNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
