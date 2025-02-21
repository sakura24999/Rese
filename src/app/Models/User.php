<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use App\Models\Shop;
use Laravel\Sanctum\HasApiTokens;

/**
 * Summary of User
 *
 * @method bool hasRole(string $roleName)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Shop::class, 'favorites', 'user_id', 'shop_id')->withTimestamps();
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function sendEmailVerificationNotification()
    {
        Log::info('Sending verification email', ['user_id' => $this->id]);
        parent::sendEmailVerificationNotification();
    }
    public function manageShops()
    {
        return $this->belongsToMany(Shop::class, 'shop_representatives')->withPivot('is_primary', 'approved_at')->withTimestamps();
    }
    public function isShopOwner($shopId = null)
    {
        $query = $this->manageShops();
        if ($shopId) {
            $query->where('shop_id', $shopId);
        }
        return $query->exists();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /** @param string $roleName
     * @return bool
     */
    public function hasRole(string $roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /** @param array $roleNames
     *  @return bool
     */
    public function hasAnyRole($roleNames)
    {
        return $this->roles()->whereIn('name', (array) $roleNames)->exists();
    }
}
