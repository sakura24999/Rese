<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Search parameters:', [
            'name' => $request->name,
            'area' => $request->area,
            'genre' => $request->genre
        ]);

        $query = Shop::query();

        if ($request->filled('name')) {
            $query->search($request->name);

            Log::info('Search query:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);
        }
        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }
        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }

        $shops = $query->with('favorites')->paginate(20);

        $areas = Shop::distinct()->pluck('area');
        $genres = Shop::distinct()->pluck('genre');

        $shops->appends($request->all());

        Log::info('Search results:', [
            'count' => $shops->total()
        ]);

        return view('shops.index', compact('shops', 'areas', 'genres'));
    }

    public function show(Shop $shop)
    {
        Log::info('Shop show method called', [
            'shop_id' => $shop->id,
            'shop_name' => $shop->name,
            'user_id' => auth()->id() ?? 'guest',
            'request_url' => request()->fullUrl()
        ]);

        $isFavorited = auth()->check() ? $shop->favorites()->where('user_id', auth()->id())->exists() : false;

        $availableTimes = $this->generateAvailableTimes($shop->genre);

        $reviews = $shop->reviews()->with('user')->latest()->get();

        $averageRating = $shop->averageRating();

        return view('shops.show', compact('shop', 'isFavorited', 'availableTimes', 'reviews', 'averageRating'));
    }

    public function search(Request $request)
    {
        $query = Shop::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }
        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }

        $shops = $query->limit(5)->get();

        return response()->json($shops);
    }

    private function generateAvailableTimes($genre)
    {
        $times = [];

        $hours = match ($genre) {
            '寿司' => ['start' => '11:30', 'end' => '22:00'],
            '焼肉' => ['start' => '17:00', 'end' => '23:00'],
            'イタリアン' => ['start' => '11:30', 'end' => '22:30'],
            'ラーメン' => ['start' => '11:00', 'end' => '22:00'],
            default => ['start' => '11:00', 'end' => '22:00'],
        };

        $start = strtotime($hours['start']);
        $end = strtotime($hours['end']);

        for ($time = $start; $time <= $end; $time = strtotime('+30 minutes', $time)) {
            $times[] = date('H:i', $time);
        }

        return $times;
    }
}
