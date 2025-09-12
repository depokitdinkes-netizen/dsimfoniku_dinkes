<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function auth(Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return redirect(route('login'))->with('error', 'email atau password salah');
        }

        $request->session()->regenerate();
        return redirect(route('dashboard'));
    }

    public function deauth(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
