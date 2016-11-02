<?php

namespace App\Providers;
use App\Http\Controllers\CartController;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('*',function($view) {
            $cart = new CartController();
            $count = $cart->getcartCount();
            $view->with('cartCount', $count);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
