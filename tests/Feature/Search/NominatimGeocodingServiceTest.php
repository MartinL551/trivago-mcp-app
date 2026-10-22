<?php

namespace Tests\Feature\Search;

use App\Models\Location;
use App\Services\NominatimGeocodingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NominatimGeocodingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_missing_landmark_retries_with_the_city_and_caches_city_coordinates(): void
    {
        Http::fakeSequence()
            ->push([])
            ->push([['lat' => '48.8566', 'lon' => '2.3522']]);

        $coordinates = app(NominatimGeocodingService::class)->geocode('Paris', 'France', 'Missing landmark');

        $this->assertSame(48.8566, $coordinates->latitude);
        $this->assertSame(2.3522, $coordinates->longitude);
        Http::assertSentCount(2);
        $queries = Http::recorded()->map(function (array $record): string {
            parse_str(parse_url($record[0]->url(), PHP_URL_QUERY), $query);

            return $query['q'];
        })->all();
        $this->assertSame(['Missing landmark,Paris,France', 'Paris,France'], $queries);
        $this->assertDatabaseHas('locations', ['city' => 'Paris', 'country' => 'France', 'landmark' => '']);
        $this->assertDatabaseMissing('locations', ['landmark' => 'Missing landmark']);
    }

    public function test_missing_landmark_can_fall_back_to_cached_city_coordinates(): void
    {
        Location::create([
            'city' => 'Paris', 'country' => 'France', 'landmark' => '',
            'latitude' => 48.8566, 'longitude' => 2.3522,
        ]);
        Http::fakeSequence()->push([]);

        $coordinates = app(NominatimGeocodingService::class)->geocode('Paris', 'France', 'Missing landmark');

        $this->assertSame(48.8566, $coordinates->latitude);
        $this->assertSame(2.3522, $coordinates->longitude);
        Http::assertSentCount(1);
    }

    public function test_missing_city_stops_retrying(): void
    {
        Http::fakeSequence()->push([])->push([]);

        $this->assertNull(app(NominatimGeocodingService::class)->geocode('Unknown city', '', 'Missing landmark'));
        Http::assertSentCount(2);
    }

    public function test_successful_landmark_coordinates_are_reused_from_cache(): void
    {
        Http::fakeSequence()->push([['lat' => '48.8584', 'lon' => '2.2945']]);
        $service = app(NominatimGeocodingService::class);

        $coordinates = $service->geocode('Paris', 'France', 'Eiffel Tower');
        $cached = $service->geocode('Paris', 'France', 'Eiffel Tower');

        $this->assertSame(48.8584, $coordinates->latitude);
        $this->assertSame(2.2945, $coordinates->longitude);
        $this->assertEquals($coordinates, $cached);
        Http::assertSentCount(1);
    }
}