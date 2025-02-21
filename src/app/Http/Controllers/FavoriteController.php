<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Psy\TabCompletion\Matcher\FunctionsMatcher;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Shop $shop)
    {
        try {
            $user = Auth::user();

            Log::info('Toggle favorite attempt', [
                'user_id' => $user->id,
                'shop_id' => $shop->id
            ]);

            $exists = $user->favorites()->where('shop_id', $shop->id)->exists();

            if ($user->favorites()->where('shop_id', $shop->id)->exists()) {
                Log::info('Removing favorite', [
                    'user_id' => $user->id,
                    'shop_id' => $shop->id
                ]);
                $user->favorites()->detach($shop->id);
                $status = 'remove';
            } else {
                Log::info('Adding favorite', [
                    'user_id' => $user->id,
                    'shop_id' => $shop->id
                ]);
                $user->favorites()->attach($shop->id);
                $status = 'added';
            }

            Log::info('Favorite toggle completed', [
                'status' => $status
            ]);

            return response()->json([
                'status' => $status,
                '$shop_id' => $shop->id
            ]);
        } catch (\Exception $e) {
            Log::error('Favorite toggle error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
                'shop_id' => $shop->id
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
