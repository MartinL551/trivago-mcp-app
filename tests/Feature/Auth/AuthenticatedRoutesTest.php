<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class AuthenticatedRoutesTest extends TestCase
{
    public function test_guest_cannot_access_authenticated_search_page(): void
    {
        $this->get(route('search.index'))
            ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_authenticated_pages(): void
    {
        $protectedRoutes = [
            route('search.show'),
            route('results.index'),
            route('wishlist.show'),
            route('profile.edit'),
        ];

        foreach ($protectedRoutes as $route) {
            $this->get($route)
                ->assertRedirect(route('login'));
        }
    }
}
