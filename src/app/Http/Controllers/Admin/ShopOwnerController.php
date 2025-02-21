<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\ShopOwner;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ShopOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shopOwners = User::whereHas('roles', function ($query) {
            $query->where('name', 'shop_owner');
        })->paginate(10);

        return view('admin.shop-owners.index', compact('shopOwners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shops = Shop::whereNull('shop_owner_id')->get();
        return view('admin.shop-owners.create', compact('shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:shop_owners'],
            'shops' => ['sometimes', 'array'],
        ]);

        $shopOwner = ShopOwner::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (isset($validated['shops']) && !empty($validated['shops'])) {
            Shop::whereIn('id', $validated['shops'])->update(['shop_owner_id' => $shopOwner->id]);
        }

        return redirect()->route('admin.shop-owners.index')->with('success', '店舗代表者を登録しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShopOwner $shopOwner)
    {
        $shopOwner->load('shops');
        return view('admin.shop-owners.show', compact('shopOwner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShopOwner $shopOwner)
    {
        $shops = Shop::where(function ($query) use ($shopOwner) {
            $query->whereNull('shop_owner_id')->orWhere('shop_owner_id', $shopOwner->id);
        })->get();

        return view('admin.shop-owner.edit', compact('shopOwner', 'shops'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopOwner $shopOwner)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:shop_owners,email,' . $shopOwner->id
            ],
            'password' => ['sometimes', 'nullable', Password::defaults()],
            'shops' => ['sometimes', 'array'],
        ]);

        $shopOwner->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $shopOwner->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        Shop::where('shop_owner_id', $shopOwner->id)->update(['shop_owner_id' => null]);

        if (isset($validated['shops']) && !empty($validated['shops'])) {
            Shop::whereIn('id', $validated['shops'])->update(['shop_owner_id' => $shopOwner->id]);
        }

        return redirect()->route('admin.shop-owners.index')->with('success', '店舗代表者情報を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopOwner $shopOwner)
    {
        Shop::where('shop_owner_id', $shopOwner->id)->update(['shop_owner_id' => null]);

        $shopOwner->delete();

        return redirect()->route('admin.shop-owners.index')->with('success', '店舗代表者を削除しました');
    }
}
