<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(
    'city',
    'country',
    'landmark',
    'latitude',
    'longitude',
)]
class Location extends Model
{
}
