<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\TravelPost;
use App\Policies\CommentPolicy;
use App\Policies\TravelPostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        //Registro le policy ai modelli
        Gate::policy(TravelPost::class, TravelPostPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
