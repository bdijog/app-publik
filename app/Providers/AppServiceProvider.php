<?php

namespace App\Providers;

use App\Models\Employee;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
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
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch): void {
            $switch
                ->locales(['id', 'en'])
                ->labels([
                    'id' => 'Bahasa Indonesia',
                    'en' => 'English',
                ])
                ->visible(outsidePanels: true);
        });

        Gate::before(function ($user, $ability) {
            return $user instanceof Employee && $user->hasRole('admin') ? true : null;
        });

        Event::listen(Login::class, function (Login $event): void {
            if ($event->user instanceof Employee) {
                $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
            }
        });
    }
}
