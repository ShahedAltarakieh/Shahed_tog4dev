<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Category;
use App\Models\QuickContribution;
use App\Models\Item;
use App\Models\Payment;
use App\Observers\UserObserver;
use App\Observers\CategoryObserver;
use App\Observers\QuickContributionObserver;
use App\Observers\ItemObserver;
use App\Observers\PaymentObserver;
use Illuminate\Support\Facades\Request;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
