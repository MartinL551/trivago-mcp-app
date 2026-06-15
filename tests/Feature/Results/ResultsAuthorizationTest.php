<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SearchRequest;
use Tests\TestCase;

class ResultsAuthorizationTest extends TestCase
{
    public function test_user_cannot_poll_another_users_search(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $searchRequest = SearchRequest::create([
            'user_id' => $owner->id,
            'prompt' => 'Hotels in Berlin',
            'status' => 'pending',
        ]);

        $this->actingAs($otherUser)
            ->post(route('results.poll', $searchRequest))
            ->assertForbidden();
    }

    public function test_user_cannot_view_another_users_search(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $searchRequest = SearchRequest::create([
            'user_id' => $owner->id,
            'prompt' => 'Hotels in Berlin',
            'status' => 'pending',
        ]);

        $this->actingAs($otherUser)
            ->post(route('results.show', $searchRequest))
            ->assertForbidden();
    }
}
