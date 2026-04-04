<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('components.customer.bottom-nav', function ($view) {
            $cartCount = 0;

            if (Auth::check()) {
                // Ambil keranjang pending milik user
                $cart = Cart::where('user_id', Auth::id())
                            ->first();
                $cartCount = $cart ? $cart->items()->count() : 0;
            }

            $view->with('cartCount', (int)$cartCount);
        });
    }
}