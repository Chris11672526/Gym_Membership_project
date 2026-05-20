<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid admin login details.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (! in_array(Auth::user()->role?->name, ['admin', 'staff'])) {
            Auth::logout();
            return back()->withErrors(['email' => 'This login is for admin or staff only.']);
        }

        return redirect()->route('admin.dashboard');
    }
}