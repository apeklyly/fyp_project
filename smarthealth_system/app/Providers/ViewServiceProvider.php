<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Message;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Using a closure based composer...
        View::composer('layouts.app', function ($view) {
            if (Auth::check() && Auth::user()->role === 'patient') {
                $unreadMessagesCount = Message::where('receiver_id', Auth::id())
                    ->whereNull('read_at')
                    ->count();
                $view->with('unreadMessagesCount', $unreadMessagesCount);
            } else {
                $view->with('unreadMessagesCount', 0); // Default for doctors or guests
            }
        });
    }
}