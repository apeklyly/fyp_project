<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message; // <-- Add this line

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
        // This code will run every time 'layouts.app' is loaded
        View::composer('layouts.app', function ($view) {
            
            // Check if a user is logged in
            if (Auth::check()) {
                $user = Auth::user();
                
                // Get the count of unread messages for the logged-in user
                $unreadMessagesCount = Message::where('receiver_id', $user->id)
                                              ->whereNull('read_at')
                                              ->count();

                // Share this variable with the view
                $view->with('unreadMessagesCount', $unreadMessagesCount);
            } else {
                // Set a default for logged-out pages
                $view->with('unreadMessagesCount', 0);
            }
        });
    }
}