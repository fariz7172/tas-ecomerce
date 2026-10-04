<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Tampilkan Halaman Login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }
        return view('auth.login');
    }

    /**
     * Proses Login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ]);
    }

    /**
     * Tampilkan Halaman Register
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * Proses Register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat berbelanja di Mikael On Shop.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Anda telah berhasil keluar.');
    }

    /**
     * Redirect to Google OAuth Provider
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth Callback
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Pelanggan Mikael',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)),
                ]);
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Berhasil masuk dengan akun Google: ' . $user->name);
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['oauth' => 'Gagal masuk dengan Google: ' . $e->getMessage()]);
        }
    }

    /**
     * Redirect to Facebook OAuth Provider
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook OAuth Callback
     */
    public function handleFacebookCallback(Request $request)
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = User::where('facebook_id', $facebookUser->getId())
                ->orWhere('email', $facebookUser->getEmail())
                ->first();

            if ($user) {
                $user->update([
                    'facebook_id' => $facebookUser->getId(),
                    'avatar' => $facebookUser->getAvatar(),
                ]);
            } else {
                $user = User::create([
                    'name' => $facebookUser->getName() ?? 'Pelanggan Mikael',
                    'email' => $facebookUser->getEmail(),
                    'facebook_id' => $facebookUser->getId(),
                    'avatar' => $facebookUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)),
                ]);
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Berhasil masuk dengan Facebook: ' . $user->name);
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['oauth' => 'Gagal masuk dengan Facebook: ' . $e->getMessage()]);
        }
    }
}
