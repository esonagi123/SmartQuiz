<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ViewDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // dd('boot method is running');
        // if (Auth::check()) {
        //     $user = Auth::user();
        //     $userData = [
        //         'id' => $user->id,
        //         'uid' => $user->uid,
        //         'nickname' => $user->nickname,
        //         'avatar' => $user->avatar,
        //     ];

        //     View::share('userData', $userData);
        // }
    }
}
