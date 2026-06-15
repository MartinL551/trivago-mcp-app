<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(
    'accommodation_id',
    'search_request_id',
    'trivago_id',
    'romance',
    'adventure',
    'budget',
    'luxury',
    'business',
    'family',
    'why',
)]
class AccommodationScore extends Model
{
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function searchRequest(): BelongsTo
    {
        return $this->belongsTo(SearchRequest::class);
    }
}
