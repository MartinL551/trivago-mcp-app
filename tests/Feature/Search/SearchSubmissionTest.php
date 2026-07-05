<?php

namespace Tests\Feature\Search;

use App\Jobs\ProcessExtractIntentJob;
use App\Models\SearchRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SearchSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_search_post_is_rate_limited(): void
    {
        Queue::fake();

        config(['app.search_limit_per_day' => 100]);

        $user = User::factory()->create();
        RateLimiter::clear((string) $user->id);

        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($user)
                ->post(route('search.search'), [
                    'prompt' => "I want a hotel in Berlin with a spa {$i}",
                ])
                ->assertRedirect();
        }

        $this->actingAs($user)
            ->post(route('search.search'), [
                'prompt' => 'I want one more hotel in Berlin with a spa',
            ])
            ->assertTooManyRequests();
    }

    public function test_user_cannot_exceed_rolling_daily_search_usage_limit(): void
    {
        Queue::fake();

        config(['app.search_limit_per_day' => 5]);

        $user = User::factory()->create();

        SearchRequest::factory()
            ->count(5)
            ->for($user)
            ->create([
                'created_at' => now()->subHours(2),
            ]);

        $this->actingAs($user)
            ->post(route('search.search'), [
                'prompt' => 'I want a romantic hotel in Paris',
            ])
            ->assertRedirect()
            ->assertSessionHas('flash', [
                'type' => 'warning',
                'message' => 'You have reached your search limit for today. Please try again later.',
            ]);

        $this->assertDatabaseMissing('search_requests', [
            'prompt' => 'I want a romantic hotel in Paris',
        ]);

        Queue::assertNothingPushed();
    }

    public function test_user_can_search_when_older_searches_are_outside_rolling_window(): void
    {
        Queue::fake();

        config(['app.search_limit_per_day' => 5]);

        $user = User::factory()->create();

        SearchRequest::factory()
            ->count(5)
            ->for($user)
            ->create([
                'created_at' => now()->subHours(25),
            ]);

        $this->actingAs($user)
            ->post(route('search.search'), [
                'prompt' => 'I want a quiet hotel in Rome',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('search_requests', [
            'user_id' => $user->id,
            'prompt' => 'I want a quiet hotel in Rome',
            'status' => 'pending',
        ]);

        Queue::assertPushed(ProcessExtractIntentJob::class);
    }

    public function test_search_request_pipeline_is_created_from_valid_search(): void
    {
        Queue::fake();

        config(['app.search_limit_per_day' => 100]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('search.search'), [
                'prompt' => 'I want a cheap hotel near Berlin',
            ]);

        $searchRequest = SearchRequest::firstWhere('prompt', 'I want a cheap hotel near Berlin');

        $this->assertNotNull($searchRequest);
        $this->assertSame($user->id, $searchRequest->user_id);
        $this->assertSame('pending', $searchRequest->status);

        $response->assertRedirect(route('results.show', $searchRequest));

        Queue::assertPushed(ProcessExtractIntentJob::class);
    }
}
