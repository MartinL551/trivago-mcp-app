<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Suggestion extends Model
{
    public function searchRequest()
    {
        return $this->belongsToMany(SearchRequest::class)
            ->withTimestamps();
    }
}
