<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Builder; // Importer le Builder
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
        // Appliquer le scope global pour les équipes seulement si l'utilisateur est authentifié
        if (auth()->check()) {
            \Illuminate\Database\Eloquent\Model::addGlobalScope('team', function (Builder $builder) {
                $builder->where('team_id', auth()->user()->team_id); // Applique le filtre de l'équipe
            });
        }

        // Personnaliser l'URL de réinitialisation de mot de passe
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
