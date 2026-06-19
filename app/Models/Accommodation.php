<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(
    'trivago_id',
    'accommodationscore_id',
    'name',
    'currency',
    'price_per_stay',
    'price_per_night',
    'hotel_rating',
    'city',
    'review_rating',
    'review_count',
    'amenites',
    'trivago_url',
    'trivago_image_url',
    'distance_string',
    'latitude',
    'longitude',
    'arrival',
    'departure',
    'advertiser',
)]
class Accommodation extends Model
{
    public function scores(): HasMany
    {
        return $this->hasMany(AccommodationScore::class);
    }

    public function searchRequest()
    {
        return $this->belongsToMany(SearchRequest::class);
    }

    public function wishListItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }
}
