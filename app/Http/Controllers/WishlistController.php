<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller {
    //

    function index() {
        $user_id = Auth::id();
        $wishlists = Wishlist::where("user_id", $user_id)->with("product")->get();
        return view('wishlist.index', compact('wishlists'));
    }

    function addToWishlist(Request $request) {
        $user_id = Auth::id();

        $check = Wishlist::where('user_id', $user_id)->where('product_id', $request->product_id)->first();
        if ($check) {
            return redirect()->back()->with('error', 'Product already in wishlist');
        }
        Wishlist::updateOrCreate(
            [
                'user_id' => $user_id,
                'product_id' => $request->product_id,
            ],
            [
                'user_id' => $user_id,
                'product_id' => $request->product_id,
            ]
        );
        return redirect()->back()->with('status', 'Product added to wishlist');
    }

    function removeWishlist($id) {
        $user_id = Auth::id();
        Wishlist::where("id", $id)->where("user_id", $user_id)->delete();
        return redirect()->back()->with("status", "Wishlist item removed successfully");
    }

    function clearWishlist() {
        $user_id = Auth::id();
        Wishlist::where("user_id", $user_id)->delete();
        return redirect()->back()->with("status", "Wishlist cleared successfully");
    }
}
