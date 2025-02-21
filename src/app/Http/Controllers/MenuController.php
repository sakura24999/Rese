<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function showGuest(Request $request)
    {
        //return view('menu.guest');
    }

    public function showUser(Request $request)
    {
        /**
         * @var \App\Models\User $user
         */
        //$user = auth()->user();
        //Log::error('MenuController showUser Debug', [
        //'user_id' => $user->id,
        //'email' => $user->email,
        //'roles' => $user->roles->pluck('name'),
        //'has_admin_role' => $user->hasRole('admin'),
        //'auth_check' => auth()->check()
        //]);
        //return view('menus.user');
    }
}
