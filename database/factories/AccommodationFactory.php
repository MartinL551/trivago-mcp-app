<?php

namespace Database\Factories;

use App\Models\Accommodation;
use App\Models\SearchRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Accommodation>
 */
class AccommodationFactory extends Factory
{
    protected $model = Accommodation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trivago_id' => fake()->unique()->numerify('hotel-#######'),
            'search_request_id' => SearchRequest::factory(),
            'name' => fake()->company().' Hotel',
            'currency' => 'EUR',
            'price_per_stay' => 250,
            'price_per_night' => 125,
            'hotel_rating' => 4,
            'city' => 'Berlin',
            'review_rating' => 8.8,
            'review_count' => 120,
            'amenites' => 'Wifi, Spa',
            'trivago_url' => 'https://example.com/hotel',
            'trivago_image_url' => 'https://example.com/hotel.jpg',
        ];
    }
}
