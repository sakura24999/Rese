<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 0;
    const STATUS_CANCELLED = 1;

    protected $fillable = [
        'user_id',
        'shop_id',
        'date',
        'time',
        'number_of_people',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
