<?php

namespace App\Providers;

use App\Services\MatchesService;
use Illuminate\Support\Facades\Schema;
use App\Managers\winnerCheckerManager;
use Illuminate\Support\ServiceProvider;
use App\Managers\Interfaces\WinnerCheckerInterface;
use App\Services\Interfaces\MatchesServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    public $bindings = [
        MatchesServiceInterface::class => MatchesService::class,
        WinnerCheckerInterface::class => WinnerCheckerManager::class
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
