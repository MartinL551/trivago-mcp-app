<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Unguarded;


#[Unguarded]
class Accommodation extends Model
{
    private function score(): HasOne
    {
        return $this->hasOne(AccommodationScore::class);
    }

    public function getOrFetchScore(): HasOne
    {
        if(!($score = $this->score())) {
            $fetchedScore = $this->AiService->getScoreForAccommidation($this);



        }

        return $score;
    }

}
