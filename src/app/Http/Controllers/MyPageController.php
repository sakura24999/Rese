<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Favorite;

class MyPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $user = Auth::user();
            Log::info('User accessed mypage', [
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);

            $reservations = $user->reservations()->with('shop')->get();
            Log::info('Reservations retrieved', [
                'count' => $reservations->count(),
                'reservations' => $reservations->toArray()
            ]);

            $favorites = $user->favorites;
            Log::info('Favorites retrieved', [
                'count' => $favorites->count(),
                'favorites' => $favorites->toArray()
            ]);

            return view('mypage.index', compact('user', 'reservations', 'favorites'));
        } catch (\Exception $e) {
            Log::error('Error in mypage index', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            throw $e;
        }
    }
}
