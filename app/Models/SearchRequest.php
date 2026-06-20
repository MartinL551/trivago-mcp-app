<?php

namespace App\Models;

use App\Enums\SearchRequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(
    'user_id',
    'prompt',
    'main_signal',
    'secondary_signal',
    'city',
    'country',
    'landmark',
    'latitude',
    'longitude',
    'status',
)]
class SearchRequest extends Model
{
    public function setStatus(SearchRequestStatus $status): void
    {
        $this->status = $status;
        $this->save();
    }

    public function accommodations(): HasMany
    {
        return $this->hasMany(Accommodation::class)
            ->withExists([
                'wishlistItems as wishlisted' => fn ($q) => $q->where('user_id', $this->user_id),
            ]);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(AccommodationScore::class);
    }

    public function accommodationsWithScores(): HasMany
    {
        return $this->accommodations()
            ->with('scores')
            ->whereHas('scores', fn ($query) => $query->where('search_request_id', $this->id)
            )
            ->latest();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function accommodationsForStatus(): ?HasMany
    {
        return match ($this->status) {
            SearchRequestStatus::Scoring->value => $this->accommodations(),
            SearchRequestStatus::Complete->value => $this->accommodationsWithScores(),
            default => null,
        };
    }
}
