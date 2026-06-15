<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(
    'trivago_id',
    'accommodationscore_id',
    'name',
    'postcode',
    'address',
    'currency',
    'price_per_stay',
    'price_per_day',
    'rating',
    'city',
    'review_rating',
    'review_count',
    'amenites',
    'trivago_url',
    'trivago_image_url',
    'distance_string',
    'distance_to_center',
    'distance_units',
    'desc',
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
