<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function show()
    {
        return Inertia::render('Wishlist', [
            'wishListedAccoms' => Auth::user()->wishlistedAccommodationsWithScores()->get(),
        ]);
    }

    public function add(Accommodation $accommodation)
    {
        $wishlistItem = WishlistItem::updateOrCreate([
            'user_id' => Auth::id(),
            'accommodation_id' => $accommodation->id,
        ]);

        $wishlistItem->save();

        return back()->with('success', 'Added to wishlist');
    }

    public function remove(Accommodation $accommodation)
    {
       WishlistItem::where('user_id', Auth::id())
            ->where('accommodation_id', $accommodation->id)
            ->delete();

        return back()->with('success', 'Removed from wishlist');
    }
}
