<?php

namespace App\Services\Concerns;


interface GeocodingService
{
    public function geocode(string $query): Coordinates;
}