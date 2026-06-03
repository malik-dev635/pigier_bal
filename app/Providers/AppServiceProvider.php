<?php

namespace App\Providers;

use App\Support\HandleRequestsForSubdir;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Livewire\Mechanisms\HandleRequests\HandleRequests;

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
        // Compatibilité MySQL/MariaDB anciens (longueur d'index limitée à 1000).
        Schema::defaultStringLength(191);

        // --- Livewire servi depuis un sous-dossier (ex: pblog.ci/bal) ---
        // Recale l'endpoint de mise à jour (data-update-uri) avec le préfixe
        // du dossier.
        $this->app->singleton(HandleRequests::class, HandleRequestsForSubdir::class);

        // Recale aussi l'URL du script livewire.js (sinon il est chargé depuis
        // la racine du domaine et ne se charge pas).
        if ($base = request()->getBaseUrl()) {
            config(['livewire.asset_url' => $base.'/livewire/livewire.js']);
        }
    }
}
