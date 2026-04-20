<?php

namespace App\Models;

use App\Services\OpenAIService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Accommodation extends Model
{
    public function __construct(
        private OpenAIService $AiService,
    ) {
    }

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
