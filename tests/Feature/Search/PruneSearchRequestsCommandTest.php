<?php

namespace Tests\Feature\Search;

use App\Models\Accommodation;
use App\Models\SearchRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruneSearchRequestsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_prunes_old_search_requests_without_wishlisted_accommodations(): void
    {
        $oldSearchRequest = SearchRequest::factory()->create([
            'created_at' => now()->subDays(31),
        ]);
        $oldAccommodation = Accommodation::factory()
            ->for($oldSearchRequest)
            ->create();

        $this->artisan('search:prune')
            ->expectsOutput('Pruned 1 old search request(s).')
            ->assertSuccessful();

        $this->assertNull($oldSearchRequest->fresh());
        $this->assertNull($oldAccommodation->fresh());
    }
}
