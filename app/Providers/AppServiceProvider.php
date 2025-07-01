<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;

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
        View::composer('*', function ($view) {
            $pembeli = Auth::guard('pembeli')->user();
            $jumlahKeranjang = 0;

            if ($pembeli) {
                $jumlahKeranjang = Keranjang::where('pembeli_id', $pembeli->id)->count();
            }

            $view->with('jumlahKeranjang', $jumlahKeranjang);
        });
    }
}
