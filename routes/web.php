<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\Home as AdminHome;
use App\Livewire\Admin\NomineeManager;
use App\Livewire\Admin\Results;
use App\Livewire\Admin\UserManager;
use App\Livewire\Public\CandidacyForm;
use App\Livewire\Vote\CategoryList;
use App\Livewire\Vote\CategoryVote;
use App\Livewire\Vote\VoteHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Accueil → redirection selon l'état de connexion / rôle
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // Les utilisateurs connectés vont directement à leur espace.
    if (Auth::check()) {
        return redirect()->to(AuthController::homeFor(Auth::user()));
    }

    // Les visiteurs voient la page d'accueil (affiche + connexion / inscription).
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register'])->name('register.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Candidature publique (lien privé par récompense)
|--------------------------------------------------------------------------
*/
Route::get('/candidature/{token}', CandidacyForm::class)->name('candidacy.show');

/*
|--------------------------------------------------------------------------
| Espace de vote (élèves & professeurs — auth requise)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('vote')->name('vote.')->group(function () {
    Route::get('/', CategoryList::class)->name('index');
    Route::get('/historique', VoteHistory::class)->name('history');
    Route::get('/{category:slug}', CategoryVote::class)->name('category');
});

/*
|--------------------------------------------------------------------------
| Espace administrateur (auth + rôle admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminHome::class)->name('home');
    Route::get('/categories', CategoryManager::class)->name('categories');
    Route::get('/nominees', NomineeManager::class)->name('nominees');
    Route::get('/resultats', Results::class)->name('results');
    Route::get('/comptes', UserManager::class)->name('users');
    Route::get('/resultats/export', [AdminController::class, 'exportResults'])->name('results.export');
});
