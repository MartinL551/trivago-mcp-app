<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Unguarded;


#[Unguarded]
class Accommodation extends Model
{
    //unguarded for now. Need to loop back later
    public function score(): HasOne
    {
        return $this->hasOne(AccommodationScore::class);
    }

    public function searchRequest()
    {
        return $this->belongsToMany(SearchRequest::class)
            ->withTimestamps();
    }

}
