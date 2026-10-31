<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    // GET /admin/login → show the form
    public function create(): View
    {
        return view('admin.auth.login');
    }

    // POST /admin/login → check email + password
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Auth::attempt hashes the typed password and compares it with the one in the users table
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email or password is wrong.'])
                ->onlyInput('email');
        }

        // New session id after login (protects against "session fixation" attacks)
        $request->session()->regenerate();

        if (! $request->user()->isAdmin()) {
            Auth::logout();

            return back()->withErrors(['email' => 'This account is not an admin.'])->onlyInput('email');
        }

        // intended() = go to the admin page they tried to open before logging in
        return redirect()->intended(route('admin.dashboard'));
    }

    // POST /admin/logout
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
