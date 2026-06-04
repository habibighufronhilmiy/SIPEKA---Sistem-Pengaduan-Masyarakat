<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['login' => 'Gagal login dengan Google.']);
        }

        $user = User::where('social_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            if (!$user->social_id) {
                $user->update(['social_id' => $googleUser->getId(), 'social_type' => 'google']);
            }
            Auth::login($user);
            return redirect()->intended(route('dashboard'));
        }

        $username = Str::slug($googleUser->getName(), '_');
        $baseUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'name' => $googleUser->getName(),
            'username' => $username,
            'email' => $googleUser->getEmail(),
            'password' => null,
            'role' => 'masyarakat',
            'social_id' => $googleUser->getId(),
            'social_type' => 'google',
        ]);

        Auth::login($user);
        return redirect()->intended(route('dashboard'));
    }
}
