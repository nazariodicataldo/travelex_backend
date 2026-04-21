<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Comment;
use App\Models\TravelPost;
use App\Policies\CommentPolicy;
use App\Policies\TravelPostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Notifications\ResetPassword;

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
        //Creo l'URL che rimanda al front end con il form per reimpostare la password
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            $frontendUrl = Config::get('app.frontend_url');
            return "{$frontendUrl}/reset-password?token={$token}&email=". urlencode($user->email);
        });

        Password::defaults(function() {
            $rule = Password::min(8);
 
            return $this->app->isProduction()
                ? $rule->mixedCase()->uncompromised()
                : $rule;
        });

        //Registro le policy ai modelli
        Gate::policy(TravelPost::class, TravelPostPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
