<?php

namespace App\Providers;

use App\Repositories\Kendaraan\KendaraanIRepository;
use App\Repositories\Kendaraan\KendaraanRepository;
use App\Repositories\Mobil\MobilIRepository;
use App\Repositories\Mobil\MobilRepository;
use App\Repositories\Motor\MotorIRepository;
use App\Repositories\Motor\MotorRepository;
use App\Repositories\Sale\SaleIRepository;
use App\Repositories\Sale\SaleRepository;

use App\Services\Kendaraan\KendaraanIService;
use App\Services\Kendaraan\KendaraanService;
use App\Services\Mobil\MobilIService;
use App\Services\Mobil\MobilService;
use App\Services\Motor\MotorIService;
use App\Services\Motor\MotorService;
use App\Services\Sale\SaleIService;
use App\Services\Sale\SaleService;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MotorIService::class, MotorService::class);
        $this->app->bind(MobilIService::class, MobilService::class);
        $this->app->bind(KendaraanIService::class, KendaraanService::class);
        $this->app->bind(SaleIService::class, SaleService::class);

        $this->app->bind(MotorIRepository::class, MotorRepository::class);
        $this->app->bind(MobilIRepository::class, MobilRepository::class);
        $this->app->bind(KendaraanIRepository::class, KendaraanRepository::class);
        $this->app->bind(SaleIRepository::class, SaleRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
