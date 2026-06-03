<?php

namespace App\Support;

use Livewire\Mechanisms\HandleRequests\HandleRequests;

/**
 * Corrige l'URI de mise à jour Livewire quand l'application est servie depuis
 * un sous-dossier (ex: https://pblog.ci/bal).
 *
 * Laravel retire le préfixe du dossier (« base URL ») des URLs relatives.
 * Du coup Livewire génère "/livewire/update" au lieu de "/bal/livewire/update",
 * et le navigateur appelle la racine du domaine — les actions Livewire
 * (modales, votes, bascules) ne se déclenchent jamais.
 *
 * On remet le préfixe du dossier devant l'URI. En local (pas de sous-dossier),
 * getBaseUrl() renvoie "" : le comportement reste identique.
 */
class HandleRequestsForSubdir extends HandleRequests
{
    public function getUpdateUri()
    {
        return request()->getBaseUrl().parent::getUpdateUri();
    }
}
