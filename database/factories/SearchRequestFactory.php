<?php

namespace Database\Factories;

use App\Enums\SearchRequestStatus;
use App\Models\SearchRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SearchRequest>
 */
class SearchRequestFactory extends Factory
{
    protected $model = SearchRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'prompt' => 'I want a hotel in Berlin with a spa',
            'status' => SearchRequestStatus::Pending->value,
        ];
    }
}
