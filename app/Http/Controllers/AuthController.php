<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        try {
            $cekUser = User::where($field, $credentials['login'])->first();
            if (!$cekUser) {
                Log::error('LOGIN_DEBUG: User not found for ' . $field . '=' . $credentials['login']);
                return back()->withErrors(['login' => 'Login atau password salah.'])->onlyInput('login');
            }
            Log::info('LOGIN_DEBUG: User found', ['id' => $cekUser->id, 'email' => $cekUser->email, 'role' => $cekUser->role]);

            if (Auth::attempt([$field => $credentials['login'], 'password' => $credentials['password']])) {
                Log::info('LOGIN_DEBUG: Auth::attempt SUCCESS', ['id' => $cekUser->id]);
                $request->session()->regenerate();
                Log::info('LOGIN_DEBUG: Session regenerated, redirecting to dashboard');
                return redirect()->intended(route('dashboard'));
            }

            Log::warning('LOGIN_DEBUG: Auth::attempt FAILED', ['field' => $field, 'login' => $credentials['login']]);
            return back()->withErrors(['login' => 'Login atau password salah.'])->onlyInput('login');
        } catch (\Exception $e) {
            Log::error('LOGIN_DEBUG: Exception ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return back()->withErrors(['login' => 'Terjadi kesalahan: ' . $e->getMessage()])->onlyInput('login');
        }
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $passwordPlain = $validated['password'];
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'masyarakat';

        $user = User::create($validated);

        if ($user->email) {
            try {
                Mail::to($user->email)->send(new WelcomeMail($user, $passwordPlain));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email welcome: ' . $e->getMessage());
            }
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
