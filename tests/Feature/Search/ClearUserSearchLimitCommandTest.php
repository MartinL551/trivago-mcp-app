<?php

namespace Tests\Feature\Search;

use App\Models\SearchRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClearUserSearchLimitCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_moves_recent_user_searches_outside_the_limit_window(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $recentSearch = SearchRequest::factory()
            ->for($user)
            ->create(['created_at' => now()->subHour()]);

        $oldSearch = SearchRequest::factory()
            ->for($user)
            ->create(['created_at' => now()->subHours(26)]);

        $otherUsersSearch = SearchRequest::factory()
            ->for($otherUser)
            ->create(['created_at' => now()->subHour()]);

        $this->artisan('search:clear-limit', ['userId' => $user->id])
            ->assertSuccessful();

        $this->assertTrue($recentSearch->fresh()->created_at->lt(now()->subDay()));
        $this->assertEquals(
            $oldSearch->created_at->toDateTimeString(),
            $oldSearch->fresh()->created_at->toDateTimeString(),
        );
        $this->assertTrue($otherUsersSearch->fresh()->created_at->gt(now()->subDay()));
    }
}
