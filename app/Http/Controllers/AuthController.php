<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    private const ROLES = ['buyer', 'seller', 'kurir'];

    public function create(Request $request, string $role): View|RedirectResponse
    {
        abort_unless(in_array($role, self::ROLES, true), 404);

        if ($request->user()) {
            return redirect()->route($request->user()->homeRoute());
        }

        return view('auth.login', ['role' => $role]);
    }

    public function store(Request $request, string $role): RedirectResponse
    {
        abort_unless(in_array($role, self::ROLES, true), 404);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $user = $request->user();

        if ($user->role !== $role) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun ini terdaftar sebagai '.$user->role.', bukan '.$role.'.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route($user->homeRoute()));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('splash');
    }
}
