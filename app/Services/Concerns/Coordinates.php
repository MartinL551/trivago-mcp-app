<?php

namespace App\Services\Concerns;

final readonly class Coordinates
{
    public function __construct(
        public float $latitude,
        public float $longitude,
    ) {}
}
