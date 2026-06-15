<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(
    'trivago_ns',
    'trivago_id',
    'id_ns',
    'location',
    'location_label',
    'location_type',
)]
class Suggestion extends Model
{
    public function searchRequest()
    {
        return $this->belongsToMany(SearchRequest::class)
            ->withTimestamps();
    }
}
