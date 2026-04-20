<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Accommodation extends Model
{
    private function score(): HasOne
    {
        return $this->hasOne(AccommodationScore::class);
    }

    public function getOrFetchScore(): HasOne
    {
        if(!($score = $this->score())) {

        }

        return $score;
    }

}
