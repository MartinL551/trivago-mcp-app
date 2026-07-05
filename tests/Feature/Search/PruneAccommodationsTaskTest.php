<?php

namespace Tests\Feature\Search;

use App\Actions\Tasks\PruneAccommodationsTask;
use App\Models\Accommodation;
use App\Models\SearchRequest;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruneAccommodationsTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_old_search_requests_that_have_no_wishlisted_accommodations(): void
    {
        $oldSearchRequest = SearchRequest::factory()->create([
            'created_at' => now()->subDays(31),
        ]);
        $oldAccommodation = Accommodation::factory()
            ->for($oldSearchRequest)
            ->create();

        $deleted = app(PruneAccommodationsTask::class)->handle();

        $this->assertSame(1, $deleted);
        $this->assertNull($oldSearchRequest->fresh());
        $this->assertNull($oldAccommodation->fresh());
    }

    public function test_it_keeps_recent_search_requests(): void
    {
        $recentSearchRequest = SearchRequest::factory()->create([
            'created_at' => now()->subDays(5),
        ]);
        $recentAccommodation = Accommodation::factory()
            ->for($recentSearchRequest)
            ->create();

        $deleted = app(PruneAccommodationsTask::class)->handle();

        $this->assertSame(0, $deleted);
        $this->assertNotNull($recentSearchRequest->fresh());
        $this->assertNotNull($recentAccommodation->fresh());
    }

    public function test_it_keeps_old_search_requests_with_wishlisted_accommodations(): void
    {
        $user = User::factory()->create();
        $oldSearchRequest = SearchRequest::factory()
            ->for($user)
            ->create([
                'created_at' => now()->subDays(31),
            ]);
        $wishlistedAccommodation = Accommodation::factory()
            ->for($oldSearchRequest)
            ->create();

        WishlistItem::create([
            'user_id' => $user->id,
            'accommodation_id' => $wishlistedAccommodation->id,
        ]);

        $deleted = app(PruneAccommodationsTask::class)->handle();

        $this->assertSame(0, $deleted);
        $this->assertNotNull($oldSearchRequest->fresh());
        $this->assertNotNull($wishlistedAccommodation->fresh());
    }
}
