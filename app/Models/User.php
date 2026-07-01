<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


#[Fillable(
    'name',
    'provider',
    'provider_id',
    'preferred_currency',
)]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    // app/Models/User.php
    
    public function searchRequests(): HasMany
    {
        return $this->hasMany(SearchRequest::class);
    }

    public function wishListItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function wishlistedAccommodations()
    {
        return $this->belongsToMany(
            Accommodation::class,
            'wishlist_items'
        )->withExists([
            'wishlistItems as wishlisted' => fn ($q) => $q->where('user_id', $this->id),
        ]);
    }

    public function wishlistedAccommodationsWithScores()
    {
        return $this->wishlistedAccommodations()
            ->with('scores');
    }
}
