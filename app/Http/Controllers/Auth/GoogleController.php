<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Throwable;

class GoogleController extends Controller
{
    /**
     * Redirige al usuario a la pantalla de consentimiento de Google.
     */
    public function redirect(): RedirectResponse
    {
        if (! config('services.google.client_id')) {
            return redirect()->route('login')
                ->withErrors(['google' => 'El acceso con Google todavía no está configurado.']);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Procesa la respuesta de Google.
     *
     * Por seguridad, sólo se permite iniciar sesión a usuarios que ya
     * existen en la base: nunca se crean cuentas nuevas desde aquí.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException|Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['google' => 'No pudimos completar el acceso con Google. Intentá de nuevo.']);
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (! $user) {
            return redirect()->route('login')
                ->withErrors(['google' => 'Esta cuenta de Google no tiene acceso al panel.']);
        }

        // Vincula el google_id la primera vez y refresca el avatar.
        $user->forceFill([
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
        ])->save();

        Auth::login($user, remember: true);

        return redirect()->intended(route('admin.projects.index'));
    }
}
