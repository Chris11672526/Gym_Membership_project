<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * AdminAuthController
 * ─────────────────────────────────────────────────────────────────────────────
 * ZERO Eloquent ORM. Role check done via DB::table() join.
 */
class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Invalid admin login details.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // Check role using Query Builder — NOT Eloquent
        $roleName = DB::table('roles')
            ->join('users', 'users.role_id', '=', 'roles.id')
            ->where('users.id', Auth::id())
            ->value('roles.name');

        if (! in_array($roleName, ['admin', 'staff'])) {
            Auth::logout();
            return back()->withErrors(['email' => 'This login is for admin or staff only.']);
        }

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}