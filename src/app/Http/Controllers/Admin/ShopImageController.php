<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ShopImageController extends Controller
{
    public function index()
    {
        Log::info('Storage path:' . storage_path('app/public/shops'));
        $images = Storage::disk('public')->files('shops');
        return view('admin.shop_images.index', compact('images'));
    }

    public function create()
    {
        return view('admin.shop_images.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,git|max:2048',
            'name' => 'required|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'shop_' . Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();

            $path = $image->storeAs('shops', $filename, 'public');

            return redirect()->route('admin.shop-images.index')->with('success', '画像が正常にアップロードされました');
        }

        return back()->with('error', '画像のアップロードに失敗しました');
    }

    public function destroy($filename)
    {
        $path = 'shops/' . $filename;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return redirect()->route('admin.shop-images.index')->with('success', '画像が正常に削除されました');
        }
        return back()->with('error', '画像の削除に失敗しました');
    }
}
