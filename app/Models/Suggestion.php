<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Suggestion extends Model
{
    public function suggestions()
    {
        return $this->belongsToMany(SearchRequest::class)
            ->withTimestamps();
    }
}
