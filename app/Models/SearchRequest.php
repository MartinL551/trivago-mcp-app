<?php

namespace App\Models;

use App\Enums\SearchRequestStatus;
use Database\Factories\SearchRequestFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
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
    /** @use HasFactory<SearchRequestFactory> */
    use HasFactory, Prunable;

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

    public function prunable(): Builder
    {
        return static::query()
            ->where('created_at', '<=', now()->subDays(config('app.search_prune_period_days')))
            ->whereDoesntHave('accommodations.wishListItems');
    }

    public function accommodationsWithScores(): HasMany
    {
        return $this->accommodations()->with('scores');
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
