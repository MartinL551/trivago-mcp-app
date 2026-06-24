<?php

namespace App\Services;

use App\Services\Concerns\Coordinates;

class DistanceService
{

    public function between(
        Coordinates $from,
        Coordinates $to,
    ): float {
        $lat1 = $from->latitude;
        $lon1 = $from->longitude;
        $lat2 = $to->latitude;
        $lon2 = $to->longitude;

        return $this->calculateDistance($lat1, $lon1, $lat2, $lon2);
    }

    // uses Haversine Formula.
    public function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371; // km

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);

        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) ** 2 +
            cos($latFrom) *
            cos($latTo) *
            sin($lonDelta / 2) ** 2;

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        return $earthRadius * $c;
    }
}