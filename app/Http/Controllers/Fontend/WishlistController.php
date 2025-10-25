<?php

namespace App\Http\Controllers\Fontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function AddToWishlist(Request $request, $property_id){
        if (auth()->check()) {

            // Check if the property is already in the wishlist
            $exists = Wishlist::where('user_id', Auth::id())
                    ->where('property_id', $property_id)
                    ->exists();

            if ($exists) {
                return response()->json(['error' => 'Property already in wishlist']);
            }

            Wishlist::create([
                'user_id' => Auth::id(),
                'property_id' => $property_id,
                'created_at' => Carbon::now(),
            ]);

            return response()->json(['success' => 'Property added to wishlist']);
        } else {
            return response()->json(['error' => 'You must be logged in to add to wishlist']);
        }
    }

    public function UserWishlist(){
        $wishlists = Wishlist::where('user_id', Auth::id())->latest()->get();
        // return $wishlists;
        return view('frontend.wishlist.wishlist_view', compact('wishlists'));
    }

    public function GetWishlistProperty(){
        $wishlists = Wishlist::with('property')->where('user_id', Auth::id())->latest()->get();

        $wishQty = Wishlist::count();
        return response()->json(['wishlists' => $wishlists, 'wishQty' => $wishQty]);
    }

    public function RemoveToWishlist($id){
        Wishlist::where('user_id', Auth::id())->where('id', $id)->delete();

        return response()->json(['success' => 'Property removed from wishlist']);
    }
}
