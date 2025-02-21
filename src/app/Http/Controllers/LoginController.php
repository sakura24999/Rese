<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function logout(Request $request)
    {
        $user = Auth::user();
        $redirectTo = 'menu.guest';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectTo);
    }

    
    public function redirectTo()
    {
        if (auth()->check()) {
            if (auth()->user()->hasRole('admin')) {
                return '/admin';
            }
            return '/menu/user';
        }
        return '/menu/guest';
    }
}
