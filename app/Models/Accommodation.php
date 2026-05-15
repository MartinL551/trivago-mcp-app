<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Unguarded]
class Accommodation extends Model
{
    //unguarded for now. Need to loop back later
    public function scores(): HasMany
    {
        return $this->hasMany(AccommodationScore::class);
    }

    public function searchRequest()
    {
        return $this->belongsToMany(SearchRequest::class);
    }

}
