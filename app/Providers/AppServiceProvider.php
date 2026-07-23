<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\Affectation;
use App\Models\Etablissement; 

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
    Gate::define('saisir-notes', function ($user, int $matiereId, int $classeId) {
        if ($user->hasRole('admin') || $user->hasRole('proviseur')) {
            return true; // accès total pour supervision
        }

        if (!$user->hasRole('enseignant')) {
            return false;
        }

        $enseignant = $user->enseignant; // relation à ajouter sur User
        return $enseignant && $enseignant->peutSaisir($matiereId, $classeId);
    });

    // Injecter $etablissement dans toutes les vues
        View::composer('*', function ($view) {
            try {
                $view->with('etablissement', Etablissement::instance());
            } catch (\Exception $e) {
                // Table pas encore créée (avant migration)
            }
        });

        Gate::define('saisir-notes', function ($user, int $matiereId, int $classeId) {
            if ($user->hasRole('admin') || $user->hasRole('proviseur')) return true;
            if (!$user->hasRole('enseignant')) return false;
            $enseignant = $user->enseignant;
            return $enseignant && $enseignant->peutSaisir($matiereId, $classeId);
        });
}
}
