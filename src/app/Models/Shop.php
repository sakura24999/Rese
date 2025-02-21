<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_kana',
        'name_hira',
        'area',
        'genre',
        'description',
        'image_url',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'shop_id', 'user_id')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function scopeSearch($query, $searchWord)
    {
        if (!empty($searchWord)) {
            $searchWordKana = mb_convert_kana($searchWord, 'KVC');
            $searchWordHira = mb_convert_kana($searchWord, 'HVc');

            Log::info('Search word in model:', [
                'original' => $searchWord,
                'name_kana' => $searchWordKana,
                'name_hira' => $searchWordHira
            ]);

            return $query->where(function ($q) use ($searchWord, $searchWordKana, $searchWordHira) {
                $q->where('name', 'LIKE', "%{$searchWord}%")->orWhere('name_kana', 'LIKE', "%{$searchWordKana}%")->orWhere('name_hira', 'LIKE', "%{$searchWordHira}%");
            });
        }
        return $query;
    }

    public function shopOwner()
    {
        return $this->belongsTo(ShopOwner::class);
    }
}
