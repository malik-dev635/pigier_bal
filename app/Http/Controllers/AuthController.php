<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Connexion
    |--------------------------------------------------------------------------
    */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->to(self::homeFor(Auth::user()));
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // On détecte si l'identifiant saisi est un email ou un téléphone.
        $field = self::isEmail($data['login']) ? 'email' : 'phone';

        if (! Auth::attempt([$field => $data['login'], 'password' => $data['password']], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login' => 'Identifiants incorrects.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(self::homeFor(Auth::user()));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /*
    |--------------------------------------------------------------------------
    | Inscription libre (rôle « élève » uniquement)
    |--------------------------------------------------------------------------
    */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->to(self::homeFor(Auth::user()));
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'class' => 'nullable|string|max:255',
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ], [], [
            'login' => 'identifiant',
        ]);

        // --- Protection contre les inscriptions multiples (appareil / IP) ---
        $deviceId = $request->cookie(config('voting.device_cookie_name')) ?: (string) \Illuminate\Support\Str::uuid();
        $ip = $request->ip();

        if (config('voting.block_by_device') && User::where('device_id', $deviceId)->exists()) {
            throw ValidationException::withMessages([
                'login' => 'Un compte a déjà été créé depuis cet appareil. Une seule inscription est autorisée.',
            ]);
        }

        if (config('voting.block_by_ip') && $ip && User::where('registration_ip', $ip)->exists()) {
            throw ValidationException::withMessages([
                'login' => 'Une inscription a déjà été effectuée depuis ce réseau.',
            ]);
        }

        $isEmail = self::isEmail($data['login']);

        // Validation fine + unicité selon le type d'identifiant.
        $request->validate([
            'login' => [
                $isEmail ? 'email' : 'string',
                Rule::unique('users', $isEmail ? 'email' : 'phone'),
            ],
        ], [
            'login.email' => 'Veuillez saisir une adresse e-mail valide.',
            'login.unique' => 'Cet identifiant est déjà utilisé.',
        ], [
            'login' => 'identifiant',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'class' => $data['class'] ?? null,
            'email' => $isEmail ? $data['login'] : null,
            'phone' => $isEmail ? null : $data['login'],
            'password' => Hash::make($data['password']),
            'role' => 'eleve',
            'registration_ip' => $ip,
            'device_id' => $deviceId,
        ]);

        $user->assignRole('eleve');

        Auth::login($user);
        $request->session()->regenerate();

        // Marque l'appareil pour empêcher une nouvelle inscription depuis ce navigateur.
        return redirect()->route('vote.index')->withCookie(
            cookie(
                config('voting.device_cookie_name'),
                $deviceId,
                config('voting.device_cookie_minutes')
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public static function isEmail(string $value): bool
    {
        return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Redirection post-connexion selon le rôle.
     */
    public static function homeFor($user): string
    {
        return $user->isAdmin() ? route('admin.categories') : route('vote.index');
    }
}
