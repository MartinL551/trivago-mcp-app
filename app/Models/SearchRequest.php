<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use App\Enums\SearchRequestStatus;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Unguarded]
class SearchRequest extends Model
{
    //unguarded for now. Need to loop back later

    public function setStatus(SearchRequestStatus $status): void
    {
        $this->status = $status;
        $this->save();
    }

    public function suggestions(): BelongsToMany
    {
        return $this->belongsToMany(Suggestion::class);
    }

    public function accommodations(): BelongsToMany
    {
        return $this->belongsToMany(Accommodation::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(AccommodationScore::class);
    }

    public function accommodationsWithScores(): BelongsToMany
    {
        return $this->accommodations()
            ->with('scores')
            ->whereHas('scores', fn ($query) =>
                $query->where('search_request_id', $this->id)
            )
            ->latest();
    }

    public function accommodationsForStatus(): ?BelongsToMany
    {
        return match($this->status) {
            SearchRequestStatus::Scoring->value => $this->accommodations(),
            SearchRequestStatus::Complete->value => $this->accommodationsWithScores(),
            default => null,
        };
    }
}
