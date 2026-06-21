<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(
    'trivago_id',
    'search_request_id',
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
    public function scores(): HasOne
    {
        return $this->hasOne(AccommodationScore::class);
    }

    public function searchRequest(): BelongsTo
    {
        return $this->belongsTo(SearchRequest::class);
    }

    public function wishListItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }
}
